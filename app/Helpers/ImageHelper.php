<?php

namespace App\Helpers;

use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get image URL with fallback
     *
     * @param string|null $path
     * @param string $default
     * @return string
     */
    public static function getImageUrl(?string $path, string $default = '/images/no-image.png'): string
    {
        if (!$path) {
            return asset($default);
        }

        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        return asset($default);
    }

    /**
     * Get thumbnail URL
     *
     * @param string|null $path
     * @param string $size
     * @param string $default
     * @return string
     */
    public static function getThumbnailUrl(?string $path, string $size = 'thumbnail', string $default = '/images/no-image.png'): string
    {
        if (!$path) {
            return asset($default);
        }

        // Generate thumbnail path
        $pathInfo = pathinfo($path);
        $thumbnailPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . "_{$size}." . $pathInfo['extension'];

        if (Storage::disk('public')->exists($thumbnailPath)) {
            return Storage::disk('public')->url($thumbnailPath);
        }

        // Fallback to original image
        return self::getImageUrl($path, $default);
    }

    /**
     * Get image dimensions
     *
     * @param string|null $path
     * @return array|null
     */
    public static function getImageDimensions(?string $path): ?array
    {
        if (!$path || !Storage::disk('public')->exists($path)) {
            return null;
        }

        $imageService = app(ImageService::class);
        return $imageService->getDimensions($path);
    }

    /**
     * Format file size
     *
     * @param int $bytes
     * @return string
     */
    public static function formatFileSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Check if file is image
     *
     * @param string $path
     * @return bool
     */
    public static function isImage(string $path): bool
    {
        $allowedTypes = ImageService::getAllowedTypes();
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        return in_array($extension, $allowedTypes);
    }

    /**
     * Get available thumbnail sizes
     *
     * @return array
     */
    public static function getThumbnailSizes(): array
    {
        return array_keys(ImageService::getSizePresets());
    }

    /**
     * Generate responsive image srcset
     *
     * @param string|null $path
     * @param array $sizes
     * @return string
     */
    public static function generateSrcSet(?string $path, array $sizes = ['thumbnail', 'small', 'medium']): string
    {
        if (!$path) {
            return '';
        }

        $srcSet = [];
        $presets = ImageService::getSizePresets();

        foreach ($sizes as $size) {
            if (isset($presets[$size])) {
                $thumbnailUrl = self::getThumbnailUrl($path, $size);
                $width = $presets[$size]['width'];
                $srcSet[] = "{$thumbnailUrl} {$width}w";
            }
        }

        return implode(', ', $srcSet);
    }
}