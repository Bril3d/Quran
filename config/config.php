<?php
// Configuration settings for the Quran website

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'quran_db');

// API Keys and Endpoints
define('QURAN_API_URL', 'https://api.alquran.cloud/v1');
define('PRAYER_API_URL', 'https://api.aladhan.com/v1');

// Application settings
define('APP_NAME', 'Quran Website');
define('APP_URL', 'http://localhost:8000');
define('DEFAULT_LANGUAGE', 'ar');
define('DEFAULT_THEME', 'light');

// Cache settings
define('CACHE_ENABLED', true);
define('CACHE_EXPIRY', 86400); // 24 hours in seconds

// Load helper functions
require_once BASE_PATH . '/app/helpers/functions.php';
?> 