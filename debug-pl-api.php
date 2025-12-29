<?php
/**
 * Debug script untuk testing P&L API endpoint
 * Jalankan dengan: php debug-pl-api.php
 */

// Simulasi request ke API endpoint
$baseUrl = 'https://your-railway-domain.com'; // Ganti dengan domain Railway Anda
$endpoint = '/admin/expenses/profit-loss/api?period=12months&year=2025';

echo "Testing P&L API Endpoint\n";
echo "========================\n";
echo "URL: {$baseUrl}{$endpoint}\n\n";

// Test dengan cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// Add headers untuk authentication jika diperlukan
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json',
    'User-Agent: Debug-Script/1.0'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Status Code: {$httpCode}\n";

if ($error) {
    echo "cURL Error: {$error}\n";
} else {
    echo "Response:\n";
    echo "=========\n";
    
    $data = json_decode($response, true);
    if ($data) {
        echo json_encode($data, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Raw Response:\n";
        echo $response . "\n";
    }
}

echo "\nDebugging Checklist:\n";
echo "===================\n";
echo "1. ✓ Check if Railway app is running\n";
echo "2. ✓ Check database connection\n";
echo "3. ✓ Check if routes are registered\n";
echo "4. ✓ Check authentication middleware\n";
echo "5. ✓ Check if there's sample data in database\n";
echo "6. ✓ Check server logs for errors\n";