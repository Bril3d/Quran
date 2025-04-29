<?php
/**
 * API Router
 * Routes API requests to the appropriate controllers
 */
class ApiRouter {
    /**
     * Route the API request
     * 
     * @param string $request The request URI
     * @return void
     */
    public function route($request) {
        // Remove /api/ from the beginning of the request
        $request = substr($request, 5);
        
        // Parse the request path
        $path = explode('/', $request);
        
        // Get the API endpoint
        $endpoint = isset($path[0]) ? $path[0] : '';
        
        // Log the API request details
        error_log("API Router: Endpoint = '$endpoint', Full path = " . print_r($path, true));
        
        // Handle the API request based on the endpoint
        switch ($endpoint) {
            case 'quran':
                $this->handleQuranApi($path);
                break;
            case 'prayer-times':
                $this->handlePrayerTimesApi($path);
                break;
            case 'chat':
                $this->handleChatApi($path);
                break;
            case 'audio':
                $this->handleAudioApi($path);
                break;
            case 'user':
                $this->handleUserApi($path);
                break;
            default:
                $this->sendResponse(['error' => 'Invalid API endpoint: ' . $endpoint], 404);
                break;
        }
    }
    
    /**
     * Handle Quran API requests
     * 
     * @param array $path The request path parts
     * @return void
     */
    private function handleQuranApi($path) {
        $action = isset($path[1]) ? $path[1] : '';
        
        require_once BASE_PATH . '/app/controllers/QuranController.php';
        $controller = new QuranController();
        
        switch ($action) {
            case 'ayah-audio':
                $controller->getAyahAudio();
                break;
            case 'add-bookmark':
                $controller->addBookmark();
                break;
            case 'remove-bookmark':
                $controller->removeBookmark();
                break;
            default:
                $this->sendResponse(['error' => 'Invalid Quran API action: ' . $action], 404);
                break;
        }
    }
    
    /**
     * Handle Prayer Times API requests
     * 
     * @param array $path The request path parts
     * @return void
     */
    private function handlePrayerTimesApi($path) {
        $action = isset($path[1]) ? $path[1] : '';
        
        require_once BASE_PATH . '/app/controllers/PrayerController.php';
        $controller = new PrayerController();
        
        switch ($action) {
            case 'by-coordinates':
                $controller->getPrayerTimesByCoordinates();
                break;
            case 'by-city':
                $controller->getPrayerTimesByCity();
                break;
            default:
                $this->sendResponse(['error' => 'Invalid Prayer Times API action'], 404);
                break;
        }
    }
    
    /**
     * Handle Chat API requests
     * 
     * @param array $path The request path parts
     * @return void
     */
    private function handleChatApi($path) {
        $action = isset($path[1]) ? $path[1] : '';
        
        require_once BASE_PATH . '/app/controllers/ChatController.php';
        $controller = new ChatController();
        
        switch ($action) {
            case 'recommendation':
                $controller->getRecommendation();
                break;
            default:
                $this->sendResponse(['error' => 'Invalid Chat API action'], 404);
                break;
        }
    }
    
    /**
     * Handle Audio API requests
     * 
     * @param array $path The request path parts
     * @return void
     */
    private function handleAudioApi($path) {
        $action = isset($path[1]) ? $path[1] : '';
        
        require_once BASE_PATH . '/app/controllers/AudioController.php';
        $controller = new AudioController();
        
        switch ($action) {
            case 'download':
                $controller->downloadSurah();
                break;
            default:
                $this->sendResponse(['error' => 'Invalid Audio API action'], 404);
                break;
        }
    }
    
    /**
     * Handle User API requests
     * 
     * @param array $path The request path parts
     * @return void
     */
    private function handleUserApi($path) {
        $action = isset($path[1]) ? $path[1] : '';
        
        require_once BASE_PATH . '/app/controllers/AuthController.php';
        $controller = new AuthController();
        
        switch ($action) {
            case 'login':
                $controller->doLogin();
                break;
            case 'register':
                $controller->doRegister();
                break;
            case 'logout':
                $controller->logout();
                break;
            default:
                $this->sendResponse(['error' => 'Invalid User API action'], 404);
                break;
        }
    }
    
    /**
     * Send a JSON response
     * 
     * @param mixed $data The response data
     * @param int $status The HTTP status code
     * @return void
     */
    private function sendResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?> 