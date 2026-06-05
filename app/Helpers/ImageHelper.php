<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Compress and resize an uploaded image.
     * Converts all images to JPEG with 80% quality, max 1200px on longest side.
     *
     * @param string $sourcePath  Path to the uploaded temp file
     * @param string $destPath    Full destination path for the output file
     * @param int    $maxDimension Max width/height in pixels (default 1200)
     * @param int    $quality     JPEG quality 0-100 (default 80)
     * @return bool
     */
    public static function compressAndSave(string $sourcePath, string $destPath, int $maxDimension = 1200, int $quality = 80): bool
    {
        if (!function_exists('imagecreatefromjpeg')) {
            // GD not available, fall back to plain move
            return copy($sourcePath, $destPath);
        }

        $info = @getimagesize($sourcePath);
        if (!$info) return false;

        $mime = $info['mime'];

        // Create source image resource
        $srcImage = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImage = @imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = @imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $srcImage = @imagecreatefromwebp($sourcePath);
                break;
            case 'image/gif':
                $srcImage = @imagecreatefromgif($sourcePath);
                break;
            default:
                return false;
        }

        if (!$srcImage) return false;

        $origW = imagesx($srcImage);
        $origH = imagesy($srcImage);

        // Calculate new dimensions maintaining aspect ratio
        if ($origW > $maxDimension || $origH > $maxDimension) {
            if ($origW >= $origH) {
                $newW = $maxDimension;
                $newH = (int) round($origH * ($maxDimension / $origW));
            } else {
                $newH = $maxDimension;
                $newW = (int) round($origW * ($maxDimension / $origH));
            }
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        // Create destination canvas (true color)
        $dstImage = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG/WebP → fill with white background for JPEG output
        $white = imagecolorallocate($dstImage, 255, 255, 255);
        imagefill($dstImage, 0, 0, $white);

        // Resample
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Always save as JPEG (best compression for photos)
        $result = imagejpeg($dstImage, $destPath, $quality);

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $result;
    }

    /**
     * Process uploaded files array and return saved paths.
     *
     * @param array  $files     $_FILES['images'] array
     * @param string $uploadDir Full filesystem path to upload directory
     * @param int    $maxFiles  Maximum number of images to process
     * @return array  Array of relative paths like ['uploads/products/xxx.jpg']
     */
    public static function processUploads(array $files, string $uploadDir, int $maxFiles = 5): array
    {
        $images = [];
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        $count   = 0;

        foreach ($files['tmp_name'] as $key => $tmpName) {
            if ($count >= $maxFiles) break;
            if ($files['error'][$key] !== UPLOAD_ERR_OK || !$tmpName) continue;

            // Validate MIME type (more reliable than extension)
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpName);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowed)) continue;

            // Always save as .jpg after compression
            $filename = \Ramsey\Uuid\Uuid::uuid4()->toString() . '.jpg';
            $destPath = $uploadDir . $filename;

            if (self::compressAndSave($tmpName, $destPath)) {
                $images[] = 'uploads/products/' . $filename;
                $count++;
            }
        }

        return $images;
    }
}
