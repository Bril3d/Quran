<?php
require_once BASE_PATH . '/app/controllers/Controller.php';
require_once BASE_PATH . '/app/models/User.php';

/**
 * Authentication Controller
 * Handles user registration, login, and account management
 */
class AuthController extends Controller {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display the login form
     * 
     * @return void
     */
    public function login() {
        // If user is already logged in, redirect to home
        if (isLoggedIn()) {
            $this->redirect(baseUrl());
            return;
        }
        
        $this->render('auth/login');
    }
    
    /**
     * Process login form submission
     * 
     * @return void
     */
    public function doLogin() {
        $email = $this->post('email');
        $password = $this->post('password');
        $remember = $this->post('remember') ? true : false;
        
        // Validate input
        if (!$email || !$password) {
            $this->render('auth/login', [
                'error' => 'الرجاء إدخال البريد الإلكتروني وكلمة المرور',
                'email' => $email
            ]);
            return;
        }
        
        // Check if user exists
        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $this->render('auth/login', [
                'error' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة',
                'email' => $email
            ]);
            return;
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        // Set remember me cookie if requested
        if ($remember) {
            $token = $this->generateRememberToken();
            $this->userModel->updateRememberToken($user['id'], $token);
            setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
        }
        
        // Redirect to home page
        $this->redirect(baseUrl());
    }
    
    /**
     * Display the registration form
     * 
     * @return void
     */
    public function register() {
        // If user is already logged in, redirect to home
        if (isLoggedIn()) {
            $this->redirect(baseUrl());
            return;
        }
        
        $this->render('auth/register');
    }
    
    /**
     * Process registration form submission
     * 
     * @return void
     */
    public function doRegister() {
        $name = $this->post('name');
        $email = $this->post('email');
        $password = $this->post('password');
        $confirmPassword = $this->post('confirm_password');
        $terms = $this->post('terms');
        
        // Validate input
        $errors = [];
        
        if (!$name) {
            $errors['name'] = 'الرجاء إدخال الاسم';
        }
        
        if (!$email) {
            $errors['email'] = 'الرجاء إدخال البريد الإلكتروني';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'البريد الإلكتروني غير صالح';
        } elseif ($this->userModel->findByEmail($email)) {
            $errors['email'] = 'البريد الإلكتروني مستخدم بالفعل';
        }
        
        if (!$password) {
            $errors['password'] = 'الرجاء إدخال كلمة المرور';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'يجب أن تكون كلمة المرور 6 أحرف على الأقل';
        }
        
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'كلمات المرور غير متطابقة';
        }
        
        if (!$terms) {
            $errors['terms'] = 'يجب الموافقة على شروط الاستخدام وسياسة الخصوصية';
        }
        
        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => $errors,
                'name' => $name,
                'email' => $email
            ]);
            return;
        }
        
        // Create the user
        $userId = $this->userModel->create($name, $email, $password);
        
        if (!$userId) {
            $this->render('auth/register', [
                'error' => 'حدث خطأ أثناء إنشاء الحساب. الرجاء المحاولة مرة أخرى.',
                'name' => $name,
                'email' => $email
            ]);
            return;
        }
        
        // Set session
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;
        
        // Redirect to home page
        $this->redirect(baseUrl());
    }
    
    /**
     * Log the user out
     * 
     * @return void
     */
    public function logout() {
        // Clear remember token if exists
        if (isLoggedIn()) {
            $this->userModel->updateRememberToken(getCurrentUserId(), null);
        }
        
        // Clear session
        session_unset();
        session_destroy();
        
        // Clear cookies
        setcookie('remember_token', '', time() - 3600, '/');
        
        // Redirect to home page
        $this->redirect(baseUrl());
    }
    
    /**
     * Generate a random remember token
     * 
     * @return string The token
     */
    private function generateRememberToken() {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Check for remember me cookie and log user in if valid
     * This method should be called at application startup
     * 
     * @return bool Whether user was logged in
     */
    public function checkRememberToken() {
        if (isLoggedIn() || !isset($_COOKIE['remember_token'])) {
            return false;
        }
        
        $token = $_COOKIE['remember_token'];
        $user = $this->userModel->findByRememberToken($token);
        
        if (!$user) {
            return false;
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        
        return true;
    }
}
?> 