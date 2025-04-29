<?php
// Router for PHP built-in web server
if (php_sapi_name() === 'cli-server') {
    // Static files handler
    $extensions = ['php', 'jpg', 'jpeg', 'png', 'gif', 'css', 'js', 'ico', 'svg', 'ttf', 'woff', 'woff2', 'eot'];
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    
    if (empty($ext) || !in_array($ext, $extensions)) {
        // Log API requests 
        if (strpos($path, '/api/') === 0) {
            error_log("Routing API request: " . $path . " with query: " . $_SERVER['QUERY_STRING']);
        }
        
        // Route everything through index.php
        include __DIR__ . '/index.php';
        return true;
    }
    
    // Let the built-in server handle static files
    return false;
}
?> 