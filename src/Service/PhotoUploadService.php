<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PhotoUploadService
{
    public function __construct( private Filesystem $filesystem, private SluggerInterface $slugger)
    {
    }

    public function AddQuestionFile($image, $imageBaseUrl): string
    {
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

        // Move the file to the directory where brochures are stored
        try {
            $image->move(
                $imageBaseUrl,
                $newFilename
            );

        } catch (FileException $e) {
        }
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
        return $newFilename;
    }

    public function RemoveImage($image, $imageBaseUrl): void
    {
        if ($image && $this->filesystem->exists($imageBaseUrl . '/' . $image)) {
            $this->filesystem->remove($imageBaseUrl . '/' . $image);
        }
    }
}