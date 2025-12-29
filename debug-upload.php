<?php
// File untuk debug upload di Railway
// Letakkan di public/debug-upload.php dan akses via browser

echo "<h2>Railway Storage Debug</h2>";

// Check storage directories
$directories = [
    'storage/app' => storage_path('app'),
    'storage/app/public' => storage_path('app/public'),
    'storage/app/public/receipts' => storage_path('app/public/receipts'),
    'public/storage' => public_path('storage')
];

echo "<h3>Directory Status:</h3>";
foreach ($directories as $name => $path) {
    echo "<p><strong>$name:</strong> ";
    if (file_exists($path)) {
        echo "✅ EXISTS";
        if (is_writable($path)) {
            echo " (WRITABLE)";
        } else {
            echo " (NOT WRITABLE)";
        }
        echo " - " . substr(sprintf('%o', fileperms($path)), -4);
    } else {
        echo "❌ NOT EXISTS";
    }
    echo "<br>Path: $path</p>";
}

// Check symbolic link
echo "<h3>Symbolic Link Status:</h3>";
$linkPath = public_path('storage');
if (is_link($linkPath)) {
    echo "✅ Symbolic link exists<br>";
    echo "Target: " . readlink($linkPath) . "<br>";
} else {
    echo "❌ Symbolic link missing<br>";
}

// Check disk space
echo "<h3>Disk Space:</h3>";
$storagePath = storage_path('app/public');
if (file_exists($storagePath)) {
    $freeBytes = disk_free_space($storagePath);
    $totalBytes = disk_total_space($storagePath);
    echo "Free: " . formatBytes($freeBytes) . "<br>";
    echo "Total: " . formatBytes($totalBytes) . "<br>";
}

// Check PHP upload settings
echo "<h3>PHP Upload Settings:</h3>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "post_max_size: " . ini_get('post_max_size') . "<br>";
echo "max_file_uploads: " . ini_get('max_file_uploads') . "<br>";
echo "memory_limit: " . ini_get('memory_limit') . "<br>";

// Test file creation
echo "<h3>Write Test:</h3>";
$testFile = storage_path('app/public/test-write.txt');
try {
    file_put_contents($testFile, 'Test write: ' . date('Y-m-d H:i:s'));
    if (file_exists($testFile)) {
        echo "✅ Can write to storage/app/public<br>";
        unlink($testFile);
    } else {
        echo "❌ Cannot write to storage/app/public<br>";
    }
} catch (Exception $e) {
    echo "❌ Write error: " . $e->getMessage() . "<br>";
}

function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

echo "<hr>";
echo "<p><em>Delete this file after debugging!</em></p>";
?>