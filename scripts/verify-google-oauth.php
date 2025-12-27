<?php

/**
 * Google OAuth Configuration Verification Script
 * 
 * This script helps verify that Google OAuth is properly configured
 * for production deployment on Railway.
 * 
 * Usage: php scripts/verify-google-oauth.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

echo "=== Google OAuth Configuration Verification ===\n\n";

// Check if Laravel Socialite is installed
echo "1. Checking Laravel Socialite installation...\n";
if (class_exists('Laravel\Socialite\Facades\Socialite')) {
    echo "   ✅ Laravel Socialite is installed\n";
} else {
    echo "   ❌ Laravel Socialite is NOT installed\n";
    echo "   Run: composer require laravel/socialite\n";
}

// Check environment variables
echo "\n2. Checking environment variables...\n";

$requiredVars = [
    'GOOGLE_CLIENT_ID' => 'Google Client ID',
    'GOOGLE_CLIENT_SECRET' => 'Google Client Secret', 
    'GOOGLE_REDIRECT_URL' => 'Google Redirect URL',
    'APP_URL' => 'Application URL'
];

$allSet = true;
foreach ($requiredVars as $var => $description) {
    $value = $_ENV[$var] ?? getenv($var) ?? null;
    if ($value) {
        if ($var === 'GOOGLE_CLIENT_SECRET') {
            echo "   ✅ {$description}: " . str_repeat('*', strlen($value)) . "\n";
        } else {
            echo "   ✅ {$description}: {$value}\n";
        }
    } else {
        echo "   ❌ {$description}: NOT SET\n";
        $allSet = false;
    }
}

// Check redirect URL format
echo "\n3. Checking redirect URL format...\n";
$redirectUrl = $_ENV['GOOGLE_REDIRECT_URL'] ?? getenv('GOOGLE_REDIRECT_URL') ?? null;
if ($redirectUrl) {
    if (filter_var($redirectUrl, FILTER_VALIDATE_URL) && strpos($redirectUrl, 'https://') === 0) {
        echo "   ✅ Redirect URL format is valid\n";
        if (strpos($redirectUrl, '/auth/google/callback') !== false) {
            echo "   ✅ Redirect URL contains correct callback path\n";
        } else {
            echo "   ⚠️  Redirect URL should end with '/auth/google/callback'\n";
        }
    } else {
        echo "   ❌ Redirect URL must be a valid HTTPS URL\n";
    }
}

// Check APP_URL vs GOOGLE_REDIRECT_URL consistency
echo "\n4. Checking URL consistency...\n";
$appUrl = $_ENV['APP_URL'] ?? getenv('APP_URL') ?? null;
if ($appUrl && $redirectUrl) {
    $expectedRedirect = rtrim($appUrl, '/') . '/auth/google/callback';
    if ($redirectUrl === $expectedRedirect) {
        echo "   ✅ APP_URL and GOOGLE_REDIRECT_URL are consistent\n";
    } else {
        echo "   ⚠️  Expected redirect URL: {$expectedRedirect}\n";
        echo "   ⚠️  Actual redirect URL: {$redirectUrl}\n";
    }
}

// Check if routes are defined
echo "\n5. Checking route definitions...\n";
$routeFile = __DIR__ . '/../routes/auth.php';
if (file_exists($routeFile)) {
    $routeContent = file_get_contents($routeFile);
    if (strpos($routeContent, 'auth/google') !== false) {
        echo "   ✅ Google OAuth routes are defined\n";
    } else {
        echo "   ❌ Google OAuth routes are NOT defined in routes/auth.php\n";
    }
} else {
    echo "   ❌ routes/auth.php file not found\n";
}

// Check if controller exists
echo "\n6. Checking Google OAuth controller...\n";
$controllerFile = __DIR__ . '/../app/Http/Controllers/Auth/GoogleController.php';
if (file_exists($controllerFile)) {
    echo "   ✅ GoogleController exists\n";
    
    $controllerContent = file_get_contents($controllerFile);
    if (strpos($controllerContent, 'redirectToGoogle') !== false && 
        strpos($controllerContent, 'handleGoogleCallback') !== false) {
        echo "   ✅ GoogleController has required methods\n";
    } else {
        echo "   ❌ GoogleController is missing required methods\n";
    }
} else {
    echo "   ❌ GoogleController does not exist\n";
}

// Check services configuration
echo "\n7. Checking services configuration...\n";
$servicesFile = __DIR__ . '/../config/services.php';
if (file_exists($servicesFile)) {
    $servicesContent = file_get_contents($servicesFile);
    if (strpos($servicesContent, "'google'") !== false) {
        echo "   ✅ Google service configuration exists\n";
    } else {
        echo "   ❌ Google service configuration is missing from config/services.php\n";
    }
} else {
    echo "   ❌ config/services.php file not found\n";
}

// Final summary
echo "\n=== SUMMARY ===\n";
if ($allSet) {
    echo "✅ All environment variables are set!\n";
    echo "✅ Your Google OAuth should work in production.\n\n";
    echo "Next steps:\n";
    echo "1. Deploy to Railway\n";
    echo "2. Test the login flow\n";
    echo "3. Monitor for any errors\n";
} else {
    echo "❌ Some configuration is missing.\n";
    echo "Please check the items marked with ❌ above.\n\n";
    echo "Refer to docs/GOOGLE_OAUTH_SETUP.md for detailed setup instructions.\n";
}

echo "\n=== TESTING URLS ===\n";
if ($appUrl) {
    echo "Login page: {$appUrl}/login\n";
    echo "Google OAuth: {$appUrl}/auth/google\n";
    echo "OAuth callback: {$appUrl}/auth/google/callback\n";
}

echo "\n";