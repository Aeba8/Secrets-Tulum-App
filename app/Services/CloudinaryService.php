<?php

namespace App\Services;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    public function uploadImages(array $files, string $folder): array
    {
        $urls = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $urls[] = $this->uploadImage($file, $folder);
            }
        }

        return $urls;
    }

    public function uploadImage(UploadedFile $file, string $folder): string
    {
        $options = [
            'folder' => $folder,
        ];

        if (str_starts_with($file->getMimeType(), 'image/')) {
            $options['resource_type'] = 'image';
        }

        $uploaded = Cloudinary::uploadApi()->upload($file->getRealPath(), $options);

        return $uploaded['secure_url'];
    }

    public function deleteImage(string $publicId): void
    {
        Cloudinary::uploadApi()->destroy($publicId);
    }
}
