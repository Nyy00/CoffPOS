<?php
// Comprehensive Railway Debug Tool
// Place in public/ folder and access via browser
// DELETE AFTER DEBUGGING!

echo "<h1>🚂 Railway Production Debug Tool</h1>";
echo "<style>
body { font-family: Arial, sans-serif; margin: 20px; }
.success { color: green; }
.error { color: red; }
.warning { color: orange; }
.info { color: blue; }
.section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
</style>";

// 1. DATABASE CONNECTION TEST
echo "<div class='section'>";
echo "<h2>🗄️ Database Connection Test</h2>";

try {
    // Test basic connection
    $pdo = new PDO(
        "pgsql:host=" . $_ENV['PGHOST'] . ";port=" . $_ENV['PGPORT'] . ";dbname=" . $_ENV['PGDATABASE'],
        $_ENV['PGUSER'],
        $_ENV['PGPASSWORD']
    );
    echo "<p class='success'>✅ Database connection successful</p>";
    
    // Test Laravel connection
    if (file_exists('../vendor/autoload.php')) {
        require_once '../vendor/autoload.php';
        $app = require_once '../bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        
        try {
            $users = DB::table('users')->count();
            echo "<p class='success'>✅ Laravel DB connection works - Users count: $users</p>";
        } catch (Exception $e) {
            echo "<p class='error'>❌ Laravel DB error: " . $e->getMessage() . "</p>";
        }
    }
    
    // Show database info
    $stmt = $pdo->query("SELECT version()");
    $version = $stmt->fetchColumn();
    echo "<p class='info'>📊 PostgreSQL Version: $version</p>";
    
    // Check tables
    $stmt = $pdo->query("SELECT tablename FROM pg_tables WHERE schemaname = 'public' ORDER BY tablename");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "<p class='info'>📋 Tables (" . count($tables) . "): " . implode(', ', array_slice($tables, 0, 10));
    if (count($tables) > 10) echo "...";
    echo "</p>";
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p class='warning'>⚠️ Check Railway environment variables:</p>";
    echo "<ul>";
    echo "<li>PGHOST: " . ($_ENV['PGHOST'] ?? 'NOT SET') . "</li>";
    echo "<li>PGPORT: " . ($_ENV['PGPORT'] ?? 'NOT SET') . "</li>";
    echo "<li>PGDATABASE: " . ($_ENV['PGDATABASE'] ?? 'NOT SET') . "</li>";
    echo "<li>PGUSER: " . ($_ENV['PGUSER'] ?? 'NOT SET') . "</li>";
    echo "<li>PGPASSWORD: " . (isset($_ENV['PGPASSWORD']) ? '[SET]' : 'NOT SET') . "</li>";
    echo "</ul>";
}
echo "</div>";

// 2. STORAGE & FILE SYSTEM TEST
echo "<div class='section'>";
echo "<h2>💾 Storage & File System Test</h2>";

$directories = [
    'storage/app' => '../storage/app',
    'storage/app/public' => '../storage/app/public',
    'storage/app/public/receipts' => '../storage/app/public/receipts',
    'storage/app/public/images' => '../storage/app/public/images',
    'storage/logs' => '../storage/logs',
    'public/storage' => 'storage'
];

foreach ($directories as $name => $path) {
    echo "<p><strong>$name:</strong> ";
    if (file_exists($path)) {
        echo "<span class='success'>✅ EXISTS</span>";
        if (is_writable($path)) {
            echo " <span class='success'>(WRITABLE)</span>";
        } else {
            echo " <span class='error'>(NOT WRITABLE)</span>";
        }
        echo " - Permissions: " . substr(sprintf('%o', fileperms($path)), -4);
    } else {
        echo "<span class='error'>❌ NOT EXISTS</span>";
    }
    echo "</p>";
}

// Test symbolic link
$linkPath = 'storage';
echo "<p><strong>Symbolic Link:</strong> ";
if (is_link($linkPath)) {
    echo "<span class='success'>✅ EXISTS</span> → " . readlink($linkPath);
} else {
    echo "<span class='error'>❌ MISSING</span>";
}
echo "</p>";

// Test write capability
$testFile = '../storage/app/public/test-write-' . time() . '.txt';
try {
    file_put_contents($testFile, 'Test write: ' . date('Y-m-d H:i:s'));
    if (file_exists($testFile)) {
        echo "<p class='success'>✅ Can write to storage/app/public</p>";
        unlink($testFile);
    } else {
        echo "<p class='error'>❌ Cannot write to storage/app/public</p>";
    }
} catch (Exception $e) {
    echo "<p class='error'>❌ Write test failed: " . $e->getMessage() . "</p>";
}

// Disk space
$storagePath = '../storage/app/public';
if (file_exists($storagePath)) {
    $freeBytes = disk_free_space($storagePath);
    $totalBytes = disk_total_space($storagePath);
    echo "<p class='info'>💽 Free Space: " . formatBytes($freeBytes) . " / " . formatBytes($totalBytes) . "</p>";
}

echo "</div>";

// 3. ENVIRONMENT VARIABLES
echo "<div class='section'>";
echo "<h2>🔧 Environment Variables</h2>";

$importantVars = [
    'APP_ENV', 'APP_DEBUG', 'APP_URL', 'APP_KEY',
    'DB_CONNECTION', 'FILESYSTEM_DISK',
    'PGHOST', 'PGPORT', 'PGDATABASE', 'PGUSER',
    'AWS_ACCESS_KEY_ID', 'AWS_BUCKET'
];

foreach ($importantVars as $var) {
    $value = $_ENV[$var] ?? getenv($var) ?? 'NOT SET';
    if (in_array($var, ['APP_KEY', 'PGPASSWORD', 'AWS_SECRET_ACCESS_KEY'])) {
        $value = $value !== 'NOT SET' ? '[SET - ' . strlen($value) . ' chars]' : 'NOT SET';
    }
    echo "<p><strong>$var:</strong> $value</p>";
}

echo "</div>";

// 4. PHP CONFIGURATION
echo "<div class='section'>";
echo "<h2>🐘 PHP Configuration</h2>";

$phpSettings = [
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'post_max_size' => ini_get('post_max_size'),
    'max_file_uploads' => ini_get('max_file_uploads'),
    'memory_limit' => ini_get('memory_limit'),
    'max_execution_time' => ini_get('max_execution_time'),
    'file_uploads' => ini_get('file_uploads') ? 'Enabled' : 'Disabled'
];

foreach ($phpSettings as $setting => $value) {
    echo "<p><strong>$setting:</strong> $value</p>";
}

echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</p>";

echo "</div>";

// 5. LARAVEL SPECIFIC TESTS
echo "<div class='section'>";
echo "<h2>🎯 Laravel Specific Tests</h2>";

// Check if Laravel is accessible
if (file_exists('../artisan')) {
    echo "<p class='success'>✅ Laravel detected</p>";
    
    // Check config cache
    if (file_exists('../bootstrap/cache/config.php')) {
        echo "<p class='info'>📋 Config is cached</p>";
    } else {
        echo "<p class='warning'>⚠️ Config not cached</p>";
    }
    
    // Check route cache
    if (file_exists('../bootstrap/cache/routes-v7.php')) {
        echo "<p class='info'>📋 Routes are cached</p>";
    } else {
        echo "<p class='warning'>⚠️ Routes not cached</p>";
    }
    
} else {
    echo "<p class='error'>❌ Laravel not found</p>";
}

echo "</div>";

// 6. NETWORK & CONNECTIVITY
echo "<div class='section'>";
echo "<h2>🌐 Network & Connectivity</h2>";

// Test external connectivity
$testUrls = [
    'Google DNS' => 'https://dns.google',
    'AWS S3' => 'https://s3.amazonaws.com'
];

foreach ($testUrls as $name => $url) {
    $context = stream_context_create(['http' => ['timeout' => 5]]);
    $result = @file_get_contents($url, false, $context);
    if ($result !== false) {
        echo "<p class='success'>✅ Can reach $name</p>";
    } else {
        echo "<p class='error'>❌ Cannot reach $name</p>";
    }
}

echo "</div>";

// Helper function
function formatBytes($size, $precision = 2) {
    if ($size == 0) return '0 B';
    $base = log($size, 1024);
    $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}

echo "<hr>";
echo "<p class='error'><strong>⚠️ IMPORTANT: Delete this file after debugging!</strong></p>";
echo "<p><em>Generated at: " . date('Y-m-d H:i:s T') . "</em></p>";
?>