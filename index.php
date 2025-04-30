<?php
// Entry point for the Quran website application

// Define base path
define('BASE_PATH', __DIR__);

// Load configuration
require_once 'config/config.php';

// Start session
session_start();

// Router to handle requests
$request = $_SERVER['REQUEST_URI'];

// Extract the path part only (remove query string)
$path = parse_url($request, PHP_URL_PATH);

// Remove base directory from request if it exists
$basedir = '/' . basename(__DIR__);
if (strpos($path, $basedir) === 0) {
    $path = substr($path, strlen($basedir));
}

// Route the request based on the path (not including query parameters)
switch ($path) {
    case '':
    case '/':
        require __DIR__ . '/app/controllers/HomeController.php';
        $controller = new HomeController();
        $controller->index();
        break;
    case '/quran':
        require __DIR__ . '/app/controllers/QuranController.php';
        $controller = new QuranController();
        $controller->index();
        break;
    case '/audio':
        require __DIR__ . '/app/controllers/AudioController.php';
        $controller = new AudioController();
        $controller->index();
        break;
    case '/prayer-times':
        require __DIR__ . '/app/controllers/PrayerController.php';
        $controller = new PrayerController();
        $controller->index();
        break;
    case '/chat':
        require __DIR__ . '/app/controllers/ChatController.php';
        $controller = new ChatController();
        $controller->index();
        break;
    case '/login':
        require __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->doLogin();
        } else {
            $controller->login();
        }
        break;
    case '/register':
        require __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->doRegister();
        } else {
            $controller->register();
        }
        break;
    case '/logout':
        require __DIR__ . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;
    case '/profile':
        require __DIR__ . '/app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->index();
        break;
    case '/profile/update':
        require __DIR__ . '/app/controllers/ProfileController.php';
        $controller = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateProfile();
        } else {
            $controller->index();
        }
        break;
    case '/profile/history':
        require __DIR__ . '/app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->readingHistory();
        break;
    case '/settings':
        require __DIR__ . '/app/controllers/ProfileController.php';
        $controller = new ProfileController();
        $controller->settings();
        break;
    case '/settings/update':
        require __DIR__ . '/app/controllers/ProfileController.php';
        $controller = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->updateSettings();
        } else {
            $controller->settings();
        }
        break;
    default:
        // Handle API requests
        if (strpos($path, '/api/') === 0) {
            // Log API request for debugging
            $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
            error_log("API Request: " . $path . " with query: " . $query_string);
            
            require __DIR__ . '/api/ApiRouter.php';
            $router = new ApiRouter();
            $router->route($path);
            break;
        }
        
        // Handle 404 (page not found)
        http_response_code(404);
        require __DIR__ . '/app/views/404.php';
        break;
}
?> 