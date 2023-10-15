<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoUploadService
{
    public function __construct(private Filesystem $filesystem, private SluggerInterface $slugger)
    {
    }

    public function AddPhotoFile($image, $imageBaseUrl): string
    {
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

        $mineType = $image->getMimeType();
        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $imageBaseUrl,
                $newFilename
            );

        } catch (FileException $e) {
        }

        $this->resizeImage($imageBaseUrl . '/' . $newFilename, $imageBaseUrl . '/' . $newFilename, 2000000, $mineType);

        return $newFilename;
    }

    public function EditPhotoFile($oldFilename, $newImage, $imageBaseUrl): string
    {
        // Check if the old file exists before attempting to delete it
        if ($oldFilename && $this->filesystem->exists($imageBaseUrl . '/' . $oldFilename)) {
            // Delete the old image file
            $this->filesystem->remove($imageBaseUrl . '/' . $oldFilename);
        }
        $originalFilename = pathinfo($newImage->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $newImage->guessExtension();

        // Move the file to the directory where brochures are stored
        $mineType = $newImage->getMimeType();
        try {
            $newImage->move(
                $imageBaseUrl,
                $newFilename
            );

        } catch (FileException $e) {
        }

        $this->resizeImage($imageBaseUrl . '/' . $newFilename, $imageBaseUrl . '/' . $newFilename, 2000000, $mineType);

        return $newFilename;
    }

    public function RemoveImage($image, $imageBaseUrl): void
    {
        if ($image && $this->filesystem->exists($imageBaseUrl . '/' . $image)) {
            $this->filesystem->remove($imageBaseUrl . '/' . $image);
        }
    }

    public function resizeImage(string $sourcePath, string $targetPath, int $targetSizeInBytes, string $mimeType): void
    {
        $quality = 75;
        $maxIterations = 10;

        switch ($mimeType) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            default:
                throw new \InvalidArgumentException('Unsupported image type.');
        }

        for ($i = 0; $i < $maxIterations; $i++) {
            try {
                $exif = @exif_read_data($sourcePath);

                // Check for EXIF orientation and rotate the image if needed
                if (!empty($exif['Orientation'])) {
                    switch ($exif['Orientation']) {
                        case 3:
                            $image = imagerotate($image, 180, 0);
                            break;
                        case 6:
                            $image = imagerotate($image, -90, 0);
                            break;
                        case 8:
                            $image = imagerotate($image, 90, 0);
                            break;
                    }
                }
            } catch (\ErrorException $exception) {
                // Handle EXIF read error if needed
            }

            ob_start();
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                imagejpeg($image, null, $quality);
            } elseif ($mimeType === 'image/png') {
                imagepng($image, null, 9); // Use maximum compression for PNG
            }
            $imageData = ob_get_contents();
            ob_end_clean();

            imagedestroy($image);

            $currentSize = strlen($imageData);

            if ($currentSize <= $targetSizeInBytes) {
                file_put_contents($targetPath, $imageData);
                break;
            } else {
                $width = imagesx($image);
                $height = imagesy($image);
                $newWidth = $width * 0.75;
                $newHeight = $height * 0.75;
                $image = imagescale($image, $newWidth, $newHeight);

                if ($quality > 10) {
                    $quality -= 20;
                }
            }
        }
    }
}