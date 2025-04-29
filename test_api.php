<?php
// API Test Script

// Define constants needed for the test
define('BASE_PATH', __DIR__);

// Load configuration
require_once 'config/config.php';

echo "Testing connection to Quran API...\n";

// Test 1: Get list of Surahs
$url = QURAN_API_URL . '/surah';
echo "Test 1: Getting list of Surahs from {$url}\n";
$response = testApiRequest($url);

if (isset($response['data']) && is_array($response['data'])) {
    echo "SUCCESS: Received list of " . count($response['data']) . " Surahs\n";
} else {
    echo "ERROR: Failed to get list of Surahs\n";
    if (isset($response['error'])) {
        echo "Error message: " . $response['error'] . "\n";
    }
}

echo "\n";

// Test 2: Get a specific Surah
$surahNumber = 1;
$url = QURAN_API_URL . '/surah/' . $surahNumber;
echo "Test 2: Getting Surah {$surahNumber} from {$url}\n";
$response = testApiRequest($url);

if (isset($response['data']) && isset($response['data']['name'])) {
    echo "SUCCESS: Received data for Surah " . $response['data']['name'] . "\n";
} else {
    echo "ERROR: Failed to get Surah data\n";
    if (isset($response['error'])) {
        echo "Error message: " . $response['error'] . "\n";
    }
}

echo "\n";

// Test 3: Get ayahs for a specific Surah
$url = QURAN_API_URL . '/surah/' . $surahNumber . '/ar.alafasy';
echo "Test 3: Getting ayahs for Surah {$surahNumber} from {$url}\n";
$response = testApiRequest($url);

if (isset($response['data']) && isset($response['data']['ayahs']) && is_array($response['data']['ayahs'])) {
    echo "SUCCESS: Received " . count($response['data']['ayahs']) . " ayahs\n";
} else {
    echo "ERROR: Failed to get ayahs\n";
    if (isset($response['error'])) {
        echo "Error message: " . $response['error'] . "\n";
    }
}

/**
 * Make a direct API request for testing
 * 
 * @param string $url The URL to request
 * @return array The API response
 */
function testApiRequest($url) {
    echo "Making request to: {$url}\n";
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
        curl_close($ch);
        return ['error' => curl_error($ch)];
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    echo "HTTP Status Code: {$httpCode}\n";
    
    curl_close($ch);
    
    $decoded = json_decode($response, true);
    
    if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON Decode Error: " . json_last_error_msg() . "\n";
        echo "Raw Response: " . substr($response, 0, 500) . (strlen($response) > 500 ? '...' : '') . "\n";
        return ['error' => 'JSON decode error: ' . json_last_error_msg()];
    }
    
    return $decoded;
} 