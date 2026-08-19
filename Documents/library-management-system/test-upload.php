<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use App\Core\CloudinaryUploader;

// Put any small .jpg or .png in this same folder and update the filename below
$testImagePath = __DIR__ . '/test-image.jpg';

if (!file_exists($testImagePath)) {
    die("Put a test image at: $testImagePath\n");
}

$uploader = new CloudinaryUploader();
$url = $uploader->upload($testImagePath, 'library/test');

if ($url) {
    echo "Upload succeeded!\n";
    echo "URL: $url\n";
} else {
    echo "Upload failed. Check your .env credentials and the error_log.\n";
}