<?php

namespace App\Core;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;
use Exception;

class CloudinaryUploader
{
    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'] ?? '',
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'] ?? '',
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'] ?? '',
            ],
        ]);
    }

   
    public function upload(string $filePath, string $folder = 'library'): ?string
    {
        try {
            $uploadApi = new UploadApi();
            $result = $uploadApi->upload($filePath, [
                'folder' => $folder,
            ]);

            return $result['secure_url'] ?? null;

        } catch (Exception $e) {
            error_log('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }
}