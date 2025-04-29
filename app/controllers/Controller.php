<?php
/**
 * Base Controller class
 * All controllers extend this class
 */
class Controller {
    /**
     * Render a view with data
     * 
     * @param string $view The view to render
     * @param array $data Data to pass to the view
     * @return void
     */
    protected function render($view, $data = []) {
        // Add layout info to data
        $data['content_view'] = $view;
        
        // Render using the main layout
        view('layout', $data);
    }
    
    /**
     * Get POST data
     * 
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     * @return mixed The value
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    
    /**
     * Get GET data
     * 
     * @param string $key The key to get
     * @param mixed $default Default value if key doesn't exist
     * @return mixed The value
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url The URL to redirect to
     * @return void
     */
    protected function redirect($url) {
        redirect($url);
    }
    
    /**
     * Make an API request
     * 
     * @param string $url The URL to request
     * @param string $method The HTTP method
     * @param array $data Data to send with the request
     * @param bool $debug Whether to enable debugging
     * @return mixed The API response
     */
    protected function apiRequest($url, $method = 'GET', $data = [], $debug = false) {
        return apiRequest($url, $method, $data, $debug);
    }
}
?> 