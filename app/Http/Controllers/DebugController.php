<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class DebugController extends Controller
{
    /**
     * Railway Production Debug Tool
     * Only accessible in production for debugging
     */
    public function railwayDebug()
    {
        // Only allow in production or when explicitly enabled
        if (config('app.env') !== 'production' && !config('app.debug')) {
            abort(404);
        }

        $results = [];
        
        // 1. Database Connection Test
        $results['database'] = $this->testDatabase();
        
        // 2. Storage Test
        $results['storage'] = $this->testStorage();
        
        // 3. Environment Variables
        $results['environment'] = $this->getEnvironmentInfo();
        
        // 4. PHP Configuration
        $results['php'] = $this->getPhpInfo();
        
        // 5. Laravel Specific
        $results['laravel'] = $this->getLaravelInfo();
        
        // 6. File Upload Test
        $results['upload_test'] = $this->testFileUpload();

        return view('debug.railway', compact('results'));
    }

    private function testDatabase()
    {
        $result = [
            'status' => 'error',
            'message' => '',
            'details' => []
        ];

        try {
            // Test basic connection
            $pdo = DB::connection()->getPdo();
            $result['status'] = 'success';
            $result['message'] = 'Database connection successful';
            
            // Get database info
            $result['details']['driver'] = DB::connection()->getDriverName();
            $result['details']['database'] = DB::connection()->getDatabaseName();
            
            // Test query
            $userCount = DB::table('users')->count();
            $result['details']['user_count'] = $userCount;
            
            // Check tables
            $tables = DB::select("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
            $result['details']['tables'] = collect($tables)->pluck('tablename')->toArray();
            
        } catch (Exception $e) {
            $result['message'] = 'Database connection failed: ' . $e->getMessage();
            $result['details']['error'] = $e->getMessage();
            
            // Check environment variables
            $result['details']['env_vars'] = [
                'DB_CONNECTION' => config('database.default'),
                'DB_HOST' => config('database.connections.pgsql.host'),
                'DB_PORT' => config('database.connections.pgsql.port'),
                'DB_DATABASE' => config('database.connections.pgsql.database'),
                'DB_USERNAME' => config('database.connections.pgsql.username'),
                'DB_PASSWORD' => config('database.connections.pgsql.password') ? '[SET]' : '[NOT SET]'
            ];
        }

        return $result;
    }

    private function testStorage()
    {
        $result = [
            'status' => 'success',
            'directories' => [],
            'symbolic_link' => [],
            'write_test' => [],
            'disk_space' => []
        ];

        // Check directories
        $directories = [
            'storage/app' => storage_path('app'),
            'storage/app/public' => storage_path('app/public'),
            'storage/app/public/receipts' => storage_path('app/public/receipts'),
            'storage/app/public/images' => storage_path('app/public/images'),
            'storage/logs' => storage_path('logs'),
            'public/storage' => public_path('storage')
        ];

        foreach ($directories as $name => $path) {
            $info = [
                'path' => $path,
                'exists' => file_exists($path),
                'writable' => file_exists($path) ? is_writable($path) : false,
                'permissions' => file_exists($path) ? substr(sprintf('%o', fileperms($path)), -4) : 'N/A'
            ];
            $result['directories'][$name] = $info;
        }

        // Check symbolic link
        $linkPath = public_path('storage');
        $result['symbolic_link'] = [
            'exists' => is_link($linkPath),
            'target' => is_link($linkPath) ? readlink($linkPath) : 'N/A',
            'path' => $linkPath
        ];

        // Test write capability
        try {
            $testFile = 'test-write-' . time() . '.txt';
            $content = 'Test write: ' . now()->toDateTimeString();
            
            Storage::disk('public')->put($testFile, $content);
            
            if (Storage::disk('public')->exists($testFile)) {
                $result['write_test'] = [
                    'status' => 'success',
                    'message' => 'Can write to storage/app/public',
                    'file' => $testFile
                ];
                
                // Clean up
                Storage::disk('public')->delete($testFile);
            } else {
                $result['write_test'] = [
                    'status' => 'error',
                    'message' => 'File not found after write'
                ];
            }
        } catch (Exception $e) {
            $result['write_test'] = [
                'status' => 'error',
                'message' => 'Write test failed: ' . $e->getMessage()
            ];
        }

        // Disk space
        $storagePath = storage_path('app/public');
        if (file_exists($storagePath)) {
            $result['disk_space'] = [
                'free' => $this->formatBytes(disk_free_space($storagePath)),
                'total' => $this->formatBytes(disk_total_space($storagePath)),
                'free_bytes' => disk_free_space($storagePath),
                'total_bytes' => disk_total_space($storagePath)
            ];
        }

        return $result;
    }

    private function getEnvironmentInfo()
    {
        $importantVars = [
            'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_KEY',
            'DB_CONNECTION', 'FILESYSTEM_DISK',
            'CACHE_DRIVER', 'SESSION_DRIVER', 'QUEUE_CONNECTION'
        ];

        $result = [];
        foreach ($importantVars as $var) {
            $value = config(str_replace('_', '.', strtolower($var))) ?? env($var) ?? 'NOT SET';
            if (in_array($var, ['APP_KEY'])) {
                $value = $value !== 'NOT SET' ? '[SET - ' . strlen($value) . ' chars]' : 'NOT SET';
            }
            $result[$var] = $value;
        }

        return $result;
    }

    private function getPhpInfo()
    {
        return [
            'version' => PHP_VERSION,
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_file_uploads' => ini_get('max_file_uploads'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'file_uploads' => ini_get('file_uploads') ? 'Enabled' : 'Disabled',
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
        ];
    }

    private function getLaravelInfo()
    {
        return [
            'version' => app()->version(),
            'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
            'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
            'views_cached' => is_dir(storage_path('framework/views')) && count(glob(storage_path('framework/views') . '/*')) > 0,
            'filesystem_disk' => config('filesystems.default'),
            'storage_url' => Storage::url('test.jpg')
        ];
    }

    private function testFileUpload()
    {
        try {
            // Create a simple test image
            $testContent = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
            $testFile = 'test-upload-' . time() . '.png';
            
            // Test storage
            Storage::disk('public')->put($testFile, $testContent);
            
            $result = [
                'status' => 'success',
                'message' => 'File upload test successful',
                'file' => $testFile,
                'url' => Storage::url($testFile),
                'exists' => Storage::disk('public')->exists($testFile),
                'size' => Storage::disk('public')->size($testFile)
            ];
            
            // Clean up
            Storage::disk('public')->delete($testFile);
            
            return $result;
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'File upload test failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        if ($size == 0) return '0 B';
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    /**
     * Test specific upload functionality
     */
    public function testUpload(Request $request)
    {
        if (!$request->hasFile('test_file')) {
            return response()->json([
                'success' => false,
                'message' => 'No file uploaded'
            ]);
        }

        try {
            $file = $request->file('test_file');
            
            Log::info('Test upload started', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
            
            $filename = 'test-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('test-uploads', $filename, 'public');
            
            $result = [
                'success' => true,
                'message' => 'Upload successful',
                'path' => $path,
                'url' => Storage::url($path),
                'size' => $file->getSize(),
                'original_name' => $file->getClientOriginalName()
            ];
            
            Log::info('Test upload successful', $result);
            
            return response()->json($result);
            
        } catch (Exception $e) {
            Log::error('Test upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ]);
        }
    }
}