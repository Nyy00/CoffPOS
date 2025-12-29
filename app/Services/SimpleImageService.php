<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class SimpleImageService
{
    /**
     * Upload an image without processing
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
            // Debug logging for Railway
            Log::info('Upload attempt started', [
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'folder' => $folder,
                'storage_disk' => config('filesystems.default'),
                'storage_path' => storage_path('app/public'),
                'disk_free_space' => disk_free_space(storage_path('app/public')),
                'public_storage_exists' => file_exists(public_path('storage')),
                'public_storage_is_link' => is_link(public_path('storage'))
            ]);
            
            // Validate the image
            $this->validateImage($file);
            
            // Generate unique filename
            $filename = $this->generateFilename($file);
            
            // Create folder path
            $folderPath = "public/{$folder}";
            
            // Ensure directory exists
            if (!Storage::exists($folderPath)) {
                Log::info('Creating directory', ['path' => $folderPath]);
                Storage::makeDirectory($folderPath);
            }
            
            // Full path for the file
            $filePath = "{$folder}/{$filename}";
            
            // Store the file
            $stored = $file->storeAs($folder, $filename, 'public');
            
            if (!$stored) {
                throw new Exception('Failed to store image - storeAs returned false');
            }
            
            // Verify file was actually stored
            $fullStoragePath = storage_path("app/public/{$filePath}");
            if (!file_exists($fullStoragePath)) {
                throw new Exception('File was not found after storage - path: ' . $fullStoragePath);
            }
            
            Log::info('Image uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $filePath,
                'full_storage_path' => $fullStoragePath,
                'file_exists' => file_exists($fullStoragePath),
                'file_size_on_disk' => file_exists($fullStoragePath) ? filesize($fullStoragePath) : 'N/A'
            ]);
            
            return [
                'success' => true,
                'path' => $filePath,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'url' => Storage::url($filePath),
                'thumbnails' => [],
                'message' => 'Image uploaded successfully'
            ];
            
        } catch (Exception $e) {
            Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName() ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            throw new Exception('Image upload failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete an image
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
     * Validate an uploaded image
     *
     * @param UploadedFile $file
     * @return bool
     * @throws Exception
     */
    private function validateImage(UploadedFile $file): bool
    {
        // Check if file is valid
        if (!$file->isValid()) {
            $error = $file->getErrorMessage();
            Log::error('Invalid file upload', ['error' => $error]);
            throw new Exception('Invalid file upload: ' . $error);
        }
        
        // Check file size (5MB max)
        $maxSize = 5242880; // 5MB
        if ($file->getSize() > $maxSize) {
            Log::error('File size exceeds limit', [
                'file_size' => $file->getSize(),
                'max_size' => $maxSize
            ]);
            throw new Exception("File size exceeds maximum allowed size of 5MB");
        }
        
        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
        
        if (!in_array($extension, $allowedTypes)) {
            $allowedTypesStr = implode(', ', $allowedTypes);
            Log::error('Invalid file extension', [
                'extension' => $extension,
                'allowed' => $allowedTypes
            ]);
            throw new Exception("Invalid file type. Allowed types: {$allowedTypesStr}");
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
            Log::error('Invalid MIME type', [
                'mime_type' => $mimeType,
                'allowed' => $allowedMimes
            ]);
            throw new Exception('Invalid MIME type. File must be a valid image.');
        }
        
        Log::info('File validation passed', [
            'extension' => $extension,
            'mime_type' => $mimeType,
            'size' => $file->getSize()
        ]);
        
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
}
