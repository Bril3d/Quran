<?php
require_once BASE_PATH . '/app/controllers/Controller.php';
require_once BASE_PATH . '/app/models/User.php';

/**
 * Profile Controller
 * Handles user profile and settings
 */
class ProfileController extends Controller {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display the user profile
     * 
     * @return void
     */
    public function index() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->redirect(baseUrl() . '/login');
            return;
        }
        
        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            $this->redirect(baseUrl());
            return;
        }
        
        // Get user statistics
        $bookmarks = $this->userModel->getBookmarks($userId);
        $readingHistory = $this->userModel->getReadingHistory($userId, 5);
        $chatHistory = $this->userModel->getChatHistory($userId, 5);
        
        $this->render('profile/index', [
            'user' => $user,
            'bookmarks' => $bookmarks,
            'readingHistory' => $readingHistory,
            'chatHistory' => $chatHistory
        ]);
    }
    
    /**
     * Display the settings page
     * 
     * @return void
     */
    public function settings() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->redirect(baseUrl() . '/login');
            return;
        }
        
        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);
        $preferences = $this->userModel->getPreferences($userId);
        
        if (!$user) {
            $this->redirect(baseUrl());
            return;
        }
        
        $this->render('profile/settings', [
            'user' => $user,
            'preferences' => $preferences
        ]);
    }
    
    /**
     * Update user profile
     * 
     * @return void
     */
    public function updateProfile() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->redirect(baseUrl() . '/login');
            return;
        }
        
        $userId = getCurrentUserId();
        $name = $this->post('name');
        $email = $this->post('email');
        $currentPassword = $this->post('current_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');
        
        // Validate input
        $errors = [];
        $user = $this->userModel->findById($userId);
        
        if (!$name) {
            $errors['name'] = 'الرجاء إدخال الاسم';
        }
        
        if (!$email) {
            $errors['email'] = 'الرجاء إدخال البريد الإلكتروني';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'البريد الإلكتروني غير صالح';
        } elseif ($email !== $user['email']) {
            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser) {
                $errors['email'] = 'البريد الإلكتروني مستخدم بالفعل';
            }
        }
        
        // Check if password change is requested
        $changePassword = false;
        if ($currentPassword || $newPassword || $confirmPassword) {
            $changePassword = true;
            
            if (!$currentPassword) {
                $errors['current_password'] = 'الرجاء إدخال كلمة المرور الحالية';
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $errors['current_password'] = 'كلمة المرور الحالية غير صحيحة';
            }
            
            if (!$newPassword) {
                $errors['new_password'] = 'الرجاء إدخال كلمة المرور الجديدة';
            } elseif (strlen($newPassword) < 6) {
                $errors['new_password'] = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
            }
            
            if ($newPassword !== $confirmPassword) {
                $errors['confirm_password'] = 'كلمات المرور غير متطابقة';
            }
        }
        
        if (!empty($errors)) {
            $this->render('profile/index', [
                'user' => $user,
                'errors' => $errors,
                'name' => $name,
                'email' => $email,
                'bookmarks' => $this->userModel->getBookmarks($userId),
                'readingHistory' => $this->userModel->getReadingHistory($userId, 5),
                'chatHistory' => $this->userModel->getChatHistory($userId, 5)
            ]);
            return;
        }
        
        // Update user data
        $userData = [
            'name' => $name,
            'email' => $email
        ];
        
        if ($changePassword) {
            $userData['password'] = $newPassword;
        }
        
        $updated = $this->userModel->update($userId, $userData);
        
        if (!$updated) {
            $this->render('profile/index', [
                'user' => $user,
                'error' => 'حدث خطأ أثناء تحديث البيانات. الرجاء المحاولة مرة أخرى.',
                'bookmarks' => $this->userModel->getBookmarks($userId),
                'readingHistory' => $this->userModel->getReadingHistory($userId, 5),
                'chatHistory' => $this->userModel->getChatHistory($userId, 5)
            ]);
            return;
        }
        
        // Update session data
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        $this->redirect(baseUrl() . '/profile?updated=true');
    }
    
    /**
     * Update user settings
     * 
     * @return void
     */
    public function updateSettings() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->redirect(baseUrl() . '/login');
            return;
        }
        
        $userId = getCurrentUserId();
        
        // Get form data
        $preferences = [
            'quran_font' => $this->post('quran_font'),
            'quran_font_size' => (int)$this->post('quran_font_size'),
            'translation_display' => $this->post('translation_display') ? 1 : 0,
            'tafsir_display' => $this->post('tafsir_display') ? 1 : 0,
            'reciter_id' => (int)$this->post('reciter_id'),
            'auto_play_audio' => $this->post('auto_play_audio') ? 1 : 0,
            'theme' => $this->post('theme')
        ];
        
        $updated = $this->userModel->updatePreferences($userId, $preferences);
        
        if (!$updated) {
            $user = $this->userModel->findById($userId);
            $currentPreferences = $this->userModel->getPreferences($userId);
            
            $this->render('profile/settings', [
                'user' => $user,
                'preferences' => $currentPreferences,
                'error' => 'حدث خطأ أثناء تحديث الإعدادات. الرجاء المحاولة مرة أخرى.'
            ]);
            return;
        }
        
        $this->redirect(baseUrl() . '/settings?updated=true');
    }
    
    /**
     * Display full reading history
     * 
     * @return void
     */
    public function readingHistory() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $this->redirect(baseUrl() . '/login');
            return;
        }
        
        $userId = getCurrentUserId();
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            $this->redirect(baseUrl());
            return;
        }
        
        // Get page parameter for pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 20; // Items per page
        $offset = ($page - 1) * $perPage;
        
        // Get reading history with pagination
        $readingHistory = $this->userModel->getReadingHistory($userId, $perPage, $offset);
        $totalItems = $this->userModel->getReadingHistoryCount($userId);
        $totalPages = ceil($totalItems / $perPage);
        
        $this->render('profile/history', [
            'user' => $user,
            'readingHistory' => $readingHistory,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
}
?> 