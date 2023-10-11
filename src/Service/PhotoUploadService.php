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

    public function AddQuestionFile($image, $imageBaseUrl): string
    {
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $imageBaseUrl,
                $newFilename
            );

        } catch (FileException $e) {
        }

        $this->resizeImage($imageBaseUrl . '/' . $newFilename, $imageBaseUrl . '/' . $newFilename, 2000000);

        return $newFilename;
    }

    public function EditQuestionFile($oldFilename, $newImage, $imageBaseUrl): string
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
        try {
            $newImage->move(
                $imageBaseUrl,
                $newFilename
            );

        } catch (FileException $e) {
        }

        $this->resizeImage($imageBaseUrl . '/' . $newFilename, $imageBaseUrl . '/' . $newFilename, 2000000);

        return $newFilename;
    }

    public function RemoveImage($image, $imageBaseUrl): void
    {
        if ($image && $this->filesystem->exists($imageBaseUrl . '/' . $image)) {
            $this->filesystem->remove($imageBaseUrl . '/' . $image);
        }
    }

    public function resizeImage($sourcePath, $targetPath, $targetSizeInBytes): void
    {
        $quality = 75;
        $maxIterations = 10;

        for ($i = 0; $i < $maxIterations; $i++) {
            $image = imagecreatefromjpeg($sourcePath);
            $exif = exif_read_data($sourcePath);

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

            // Rest of the code remains the same
            ob_start();
            imagejpeg($image, null, $quality);
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
                $newWidth = $width * 0.9;
                $newHeight = $height * 0.9;
                $image = imagescale($image, $newWidth, $newHeight);
                $quality -= 10;
            }
        }
    }
}