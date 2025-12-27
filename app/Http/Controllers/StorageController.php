<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    /**
     * Serve files from storage/app/public
     */
    public function serve(Request $request, $path)
    {
        // Security: prevent directory traversal
        $path = str_replace(['../', '..\\'], '', $path);
        
        // Check if file exists in public disk
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }
        
        // Get the file path
        $filePath = Storage::disk('public')->path($path);
        
        // Get mime type
        $mimeType = Storage::disk('public')->mimeType($path);
        
        // Return file response with proper headers
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000),
        ]);
    }
}