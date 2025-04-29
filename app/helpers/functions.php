<?php
/**
 * Helper functions for the Quran Website
 */

/**
 * Render a view
 * 
 * @param string $view The view file to render
 * @param array $data Data to pass to the view
 * @return void
 */
function view($view, $data = []) {
    // Extract data to make it available in the view
    extract($data);
    
    // Start output buffering
    ob_start();
    
    // Include the view file but capture the output
    include BASE_PATH . '/app/views/' . $view . '.php';
    
    // Get the content from the buffer
    $content = ob_get_clean();
    
    // Display the content
    echo $content;
}

/**
 * Redirect to a specific URL
 * 
 * @param string $url The URL to redirect to
 * @return void
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Get the base URL of the application
 * 
 * @return string The base URL
 */
function baseUrl() {
    return APP_URL;
}

/**
 * Get a URL for an asset (CSS, JS, image)
 * 
 * @param string $path The path to the asset
 * @return string The complete URL to the asset
 */
function asset($path) {
    return baseUrl() . '/public/' . ltrim($path, '/');
}

/**
 * Check if a user is logged in
 * 
 * @return bool True if user is logged in, false otherwise
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 * 
 * @return int|null User ID if logged in, null otherwise
 */
function getCurrentUserId() {
    return isLoggedIn() ? $_SESSION['user_id'] : null;
}

/**
 * Cache handler for API responses
 * 
 * @param string $key The cache key
 * @param mixed $data The data to cache (if provided)
 * @param int $expiry Expiry time in seconds
 * @return mixed|null The cached data or null if not found
 */
function cache($key, $data = null, $expiry = CACHE_EXPIRY) {
    $cacheFile = BASE_PATH . '/cache/' . md5($key) . '.json';
    
    // If data is null, we're trying to get cached data
    if ($data === null) {
        if (!CACHE_ENABLED || !file_exists($cacheFile)) {
            return null;
        }
        
        $cachedData = json_decode(file_get_contents($cacheFile), true);
        
        // Check if cache is expired
        if ($cachedData['expires'] < time()) {
            unlink($cacheFile);
            return null;
        }
        
        return $cachedData['data'];
    }
    
    // If we have data, we're trying to cache it
    if (CACHE_ENABLED) {
        // Create cache directory if it doesn't exist
        if (!is_dir(BASE_PATH . '/cache')) {
            mkdir(BASE_PATH . '/cache', 0755, true);
        }
        
        // Store data in cache file
        $cachedData = [
            'expires' => time() + $expiry,
            'data' => $data
        ];
        
        file_put_contents($cacheFile, json_encode($cachedData));
    }
    
    return $data;
}

/**
 * Make an API request with caching
 * 
 * @param string $url The URL to request
 * @param string $method The HTTP method (GET, POST)
 * @param array $data Data to send with the request
 * @param bool $debug Whether to show debug information
 * @return mixed The API response
 */
function apiRequest($url, $method = 'GET', $data = [], $debug = false) {
    $cacheKey = $url . serialize($data);
    
    // Try to get from cache first
    $cachedResponse = cache($cacheKey);
    if ($cachedResponse !== null) {
        if ($debug) {
            error_log("Using cached response for: " . $url);
        }
        return $cachedResponse;
    }
    
    if ($debug) {
        error_log("Making API request to: " . $url);
    }
    
    // Make the actual API request
    $ch = curl_init();
    
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    } elseif ($method === 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
    }
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout to 30 seconds
    
    // Set SSL verification settings
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
    $response = curl_exec($ch);
    
    // Check for errors
    if (curl_errno($ch)) {
        if ($debug) {
            error_log("cURL Error: " . curl_error($ch));
        }
        curl_close($ch);
        return null;
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($debug) {
        error_log("API HTTP Code: " . $httpCode);
        error_log("API Response: " . substr($response, 0, 500) . (strlen($response) > 500 ? '...' : ''));
    }
    
    curl_close($ch);
    
    $decodedResponse = json_decode($response, true);
    
    // Check if JSON decode failed
    if ($decodedResponse === null && json_last_error() !== JSON_ERROR_NONE) {
        if ($debug) {
            error_log("JSON decode error: " . json_last_error_msg());
        }
        return null;
    }
    
    // Cache the response
    cache($cacheKey, $decodedResponse);
    
    return $decodedResponse;
}
?> 