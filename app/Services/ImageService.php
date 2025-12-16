<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Exception;

class ImageService
{
    /**
     * Allowed image types
     */
    const ALLOWED_TYPES = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
    
    /**
     * Maximum file size in bytes (default: 5MB)
     */
    const MAX_FILE_SIZE = 5242880; // 5MB
    
    /**
     * Default image quality for optimization
     */
    const DEFAULT_QUALITY = 85;
    
    /**
     * Image size presets
     */
    const SIZE_PRESETS = [
        'thumbnail' => ['width' => 150, 'height' => 150],
        'small' => ['width' => 300, 'height' => 300],
        'medium' => ['width' => 600, 'height' => 600],
        'large' => ['width' => 1200, 'height' => 1200],
    ];

    /**
     * Upload and process an image
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function upload(UploadedFile $file, string $folder = 'images', array $options = []): array
    {
        try {
            // Validate the image
            $this->validateImage($file);
            
            // Generate unique filename
            $filename = $this->generateFilename($file);
            
            // Create folder path
            $folderPath = "public/{$folder}";
            
            // Ensure directory exists
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
            }
            
            // Full path for the file
            $filePath = "{$folder}/{$filename}";
            
            // Process image based on options
            $processedImage = $this->processImage($file, $options);
            
            // Store the processed image
            $stored = Storage::disk('public')->put($filePath, $processedImage);
            
            if (!$stored) {
                throw new Exception('Failed to store image');
            }
            
            // Generate thumbnails if requested
            $thumbnails = [];
            if (isset($options['generate_thumbnails']) && $options['generate_thumbnails']) {
                $thumbnails = $this->generateThumbnails($file, $folder, $filename, $options['thumbnail_sizes'] ?? ['thumbnail', 'small']);
            }
            
            // Get file info
            $fileInfo = $this->getFileInfo($filePath);
            
            Log::info('Image uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $filePath,
                'file_size' => $fileInfo['size'],
                'thumbnails' => count($thumbnails)
            ]);
            
            return [
                'success' => true,
                'path' => $filePath,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $fileInfo['size'],
                'mime_type' => $fileInfo['mime_type'],
                'url' => Storage::disk('public')->url($filePath),
                'thumbnails' => $thumbnails,
                'message' => 'Image uploaded successfully'
            ];
            
        } catch (Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName() ?? 'unknown'
            ]);
            
            throw new Exception('Image upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete an image and its thumbnails
     *
     * @param string $path
     * @param bool $deleteThumbnails
     * @return bool
     */
    public function delete(string $path, bool $deleteThumbnails = true): bool
    {
        try {
            if (empty($path)) {
                return false;
            }
            
            // Delete main image
            $deleted = false;
            if (Storage::disk('public')->exists($path)) {
                $deleted = Storage::disk('public')->delete($path);
            }
            
            // Delete thumbnails if requested
            if ($deleteThumbnails) {
                $this->deleteThumbnails($path);
            }
            
            if ($deleted) {
                Log::info('Image deleted successfully', ['path' => $path]);
            }
            
            return $deleted;
            
        } catch (Exception $e) {
            Log::error('Image deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Resize an image
     *
     * @param string $path
     * @param int $width
     * @param int $height
     * @param bool $maintainAspectRatio
     * @param string $outputPath
     * @return array
     * @throws Exception
     */
    public function resize(string $path, int $width, int $height, bool $maintainAspectRatio = true, string $outputPath = null): array
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                throw new Exception('Image file not found');
            }
            
            // Get the full file path
            $fullPath = Storage::disk('public')->path($path);
            
            // Create image instance
            $image = Image::make($fullPath);
            
            // Resize image
            if ($maintainAspectRatio) {
                $image->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize(); // Prevent upsizing
                });
            } else {
                $image->resize($width, $height);
            }
            
            // Determine output path
            if (!$outputPath) {
                $pathInfo = pathinfo($path);
                $outputPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_resized.' . $pathInfo['extension'];
            }
            
            // Save resized image
            $outputFullPath = Storage::disk('public')->path($outputPath);
            $image->save($outputFullPath, self::DEFAULT_QUALITY);
            
            // Get file info
            $fileInfo = $this->getFileInfo($outputPath);
            
            Log::info('Image resized successfully', [
                'original_path' => $path,
                'output_path' => $outputPath,
                'dimensions' => "{$width}x{$height}",
                'maintain_aspect_ratio' => $maintainAspectRatio
            ]);
            
            return [
                'success' => true,
                'path' => $outputPath,
                'url' => Storage::disk('public')->url($outputPath),
                'width' => $image->width(),
                'height' => $image->height(),
                'size' => $fileInfo['size'],
                'message' => 'Image resized successfully'
            ];
            
        } catch (Exception $e) {
            Log::error('Image resize failed', [
                'path' => $path,
                'dimensions' => "{$width}x{$height}",
                'error' => $e->getMessage()
            ]);
            
            throw new Exception('Image resize failed: ' . $e->getMessage());
        }
    }

    /**
     * Optimize an image (compress and reduce file size)
     *
     * @param string $path
     * @param int $quality
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function optimize(string $path, int $quality = self::DEFAULT_QUALITY, array $options = []): array
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                throw new Exception('Image file not found');
            }
            
            // Get original file info
            $originalInfo = $this->getFileInfo($path);
            
            // Get the full file path
            $fullPath = Storage::disk('public')->path($path);
            
            // Create image instance
            $image = Image::make($fullPath);
            
            // Apply optimization options
            if (isset($options['max_width']) && $image->width() > $options['max_width']) {
                $image->resize($options['max_width'], null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            if (isset($options['max_height']) && $image->height() > $options['max_height']) {
                $image->resize(null, $options['max_height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Apply filters if specified
            if (isset($options['sharpen'])) {
                $image->sharpen($options['sharpen']);
            }
            
            if (isset($options['blur'])) {
                $image->blur($options['blur']);
            }
            
            // Save optimized image
            $image->save($fullPath, $quality);
            
            // Get optimized file info
            $optimizedInfo = $this->getFileInfo($path);
            
            // Calculate compression ratio
            $compressionRatio = round((1 - ($optimizedInfo['size'] / $originalInfo['size'])) * 100, 2);
            
            Log::info('Image optimized successfully', [
                'path' => $path,
                'original_size' => $originalInfo['size'],
                'optimized_size' => $optimizedInfo['size'],
                'compression_ratio' => $compressionRatio . '%',
                'quality' => $quality
            ]);
            
            return [
                'success' => true,
                'path' => $path,
                'url' => Storage::disk('public')->url($path),
                'original_size' => $originalInfo['size'],
                'optimized_size' => $optimizedInfo['size'],
                'compression_ratio' => $compressionRatio,
                'quality' => $quality,
                'width' => $image->width(),
                'height' => $image->height(),
                'message' => "Image optimized successfully. Reduced size by {$compressionRatio}%"
            ];
            
        } catch (Exception $e) {
            Log::error('Image optimization failed', [
                'path' => $path,
                'quality' => $quality,
                'error' => $e->getMessage()
            ]);
            
            throw new Exception('Image optimization failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate an uploaded image
     *
     * @param UploadedFile $file
     * @return bool
     * @throws Exception
     */
    public function validateImage(UploadedFile $file): bool
    {
        // Check if file is valid
        if (!$file->isValid()) {
            throw new Exception('Invalid file upload');
        }
        
        // Check file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            $maxSizeMB = round(self::MAX_FILE_SIZE / 1024 / 1024, 2);
            throw new Exception("File size exceeds maximum allowed size of {$maxSizeMB}MB");
        }
        
        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_TYPES)) {
            $allowedTypes = implode(', ', self::ALLOWED_TYPES);
            throw new Exception("Invalid file type. Allowed types: {$allowedTypes}");
        }
        
        // Check MIME type
        $mimeType = $file->getMimeType();
        $allowedMimes = [
            'image/jpeg',
            'image/jpg', 
            'image/png',
            'image/gif',
            'image/webp'
        ];
        
        if (!in_array($mimeType, $allowedMimes)) {
            throw new Exception('Invalid MIME type. File must be a valid image.');
        }
        
        // Check if file is actually an image
        try {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo === false) {
                throw new Exception('File is not a valid image');
            }
            
            // Check minimum dimensions
            if ($imageInfo[0] < 10 || $imageInfo[1] < 10) {
                throw new Exception('Image dimensions are too small (minimum 10x10 pixels)');
            }
            
            // Check maximum dimensions
            if ($imageInfo[0] > 5000 || $imageInfo[1] > 5000) {
                throw new Exception('Image dimensions are too large (maximum 5000x5000 pixels)');
            }
            
        } catch (Exception $e) {
            throw new Exception('Invalid image file: ' . $e->getMessage());
        }
        
        return true;
    }

    /**
     * Generate a unique filename
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $timestamp = time();
        $random = Str::random(10);
        
        return "{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Process image based on options
     *
     * @param UploadedFile $file
     * @param array $options
     * @return string
     */
    private function processImage(UploadedFile $file, array $options = []): string
    {
        // If no processing options, return original file content
        if (empty($options['resize']) && empty($options['optimize'])) {
            return file_get_contents($file->getPathname());
        }
        
        // Create image instance
        $image = Image::make($file->getPathname());
        
        // Apply resize if specified
        if (isset($options['resize'])) {
            $resize = $options['resize'];
            $image->resize(
                $resize['width'] ?? null,
                $resize['height'] ?? null,
                function ($constraint) use ($resize) {
                    if ($resize['maintain_aspect_ratio'] ?? true) {
                        $constraint->aspectRatio();
                    }
                    if ($resize['prevent_upsize'] ?? true) {
                        $constraint->upsize();
                    }
                }
            );
        }
        
        // Apply optimization
        $quality = $options['quality'] ?? self::DEFAULT_QUALITY;
        
        return $image->encode(null, $quality)->getEncoded();
    }

    /**
     * Generate thumbnails for an image
     *
     * @param UploadedFile $file
     * @param string $folder
     * @param string $filename
     * @param array $sizes
     * @return array
     */
    private function generateThumbnails(UploadedFile $file, string $folder, string $filename, array $sizes = []): array
    {
        $thumbnails = [];
        
        if (empty($sizes)) {
            $sizes = ['thumbnail', 'small'];
        }
        
        foreach ($sizes as $sizeName) {
            if (!isset(self::SIZE_PRESETS[$sizeName])) {
                continue;
            }
            
            $preset = self::SIZE_PRESETS[$sizeName];
            $pathInfo = pathinfo($filename);
            $thumbnailFilename = $pathInfo['filename'] . "_{$sizeName}." . $pathInfo['extension'];
            $thumbnailPath = "{$folder}/{$thumbnailFilename}";
            
            try {
                $image = Image::make($file->getPathname());
                $image->resize($preset['width'], $preset['height'], function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                
                $thumbnailContent = $image->encode(null, self::DEFAULT_QUALITY)->getEncoded();
                Storage::disk('public')->put($thumbnailPath, $thumbnailContent);
                
                $thumbnails[$sizeName] = [
                    'path' => $thumbnailPath,
                    'url' => Storage::disk('public')->url($thumbnailPath),
                    'width' => $image->width(),
                    'height' => $image->height()
                ];
                
            } catch (Exception $e) {
                Log::warning('Thumbnail generation failed', [
                    'size' => $sizeName,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $thumbnails;
    }

    /**
     * Delete thumbnails for an image
     *
     * @param string $path
     * @return void
     */
    private function deleteThumbnails(string $path): void
    {
        $pathInfo = pathinfo($path);
        $baseName = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        $directory = $pathInfo['dirname'];
        
        foreach (array_keys(self::SIZE_PRESETS) as $sizeName) {
            $thumbnailPath = "{$directory}/{$baseName}_{$sizeName}.{$extension}";
            
            if (Storage::disk('public')->exists($thumbnailPath)) {
                Storage::disk('public')->delete($thumbnailPath);
            }
        }
    }

    /**
     * Get file information
     *
     * @param string $path
     * @return array
     */
    private function getFileInfo(string $path): array
    {
        $fullPath = Storage::disk('public')->path($path);
        
        return [
            'size' => filesize($fullPath),
            'mime_type' => mime_content_type($fullPath),
            'last_modified' => filemtime($fullPath)
        ];
    }

    /**
     * Get image dimensions
     *
     * @param string $path
     * @return array|null
     */
    public function getDimensions(string $path): ?array
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                return null;
            }
            
            $fullPath = Storage::disk('public')->path($path);
            $imageInfo = getimagesize($fullPath);
            
            if ($imageInfo === false) {
                return null;
            }
            
            return [
                'width' => $imageInfo[0],
                'height' => $imageInfo[1],
                'type' => $imageInfo[2],
                'mime' => $imageInfo['mime']
            ];
            
        } catch (Exception $e) {
            Log::error('Failed to get image dimensions', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Convert image to different format
     *
     * @param string $path
     * @param string $format
     * @param string $outputPath
     * @return array
     * @throws Exception
     */
    public function convert(string $path, string $format, string $outputPath = null): array
    {
        try {
            if (!Storage::disk('public')->exists($path)) {
                throw new Exception('Image file not found');
            }
            
            $allowedFormats = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($format), $allowedFormats)) {
                throw new Exception('Unsupported output format');
            }
            
            // Generate output path if not provided
            if (!$outputPath) {
                $pathInfo = pathinfo($path);
                $outputPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.' . strtolower($format);
            }
            
            $fullPath = Storage::disk('public')->path($path);
            $image = Image::make($fullPath);
            
            // Convert and save
            $outputFullPath = Storage::disk('public')->path($outputPath);
            $image->encode($format, self::DEFAULT_QUALITY)->save($outputFullPath);
            
            $fileInfo = $this->getFileInfo($outputPath);
            
            return [
                'success' => true,
                'path' => $outputPath,
                'url' => Storage::disk('public')->url($outputPath),
                'format' => $format,
                'size' => $fileInfo['size'],
                'message' => "Image converted to {$format} successfully"
            ];
            
        } catch (Exception $e) {
            throw new Exception('Image conversion failed: ' . $e->getMessage());
        }
    }

    /**
     * Get size presets
     *
     * @return array
     */
    public static function getSizePresets(): array
    {
        return self::SIZE_PRESETS;
    }

    /**
     * Get allowed file types
     *
     * @return array
     */
    public static function getAllowedTypes(): array
    {
        return self::ALLOWED_TYPES;
    }

    /**
     * Get maximum file size in MB
     *
     * @return float
     */
    public static function getMaxFileSizeMB(): float
    {
        return round(self::MAX_FILE_SIZE / 1024 / 1024, 2);
    }
}