<?php 
// Set page title for layout
$pageTitle = 'تسجيل الدخول';
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>تسجيل الدخول</h2>
                <p>أدخل بيانات حسابك للوصول إلى جميع المميزات</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" action="<?= baseUrl() ?>/login" method="POST">
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">تذكرني</label>
                </div>
                
                <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
            </form>
            
            <div class="auth-links">
                <p>ليس لديك حساب؟ <a href="<?= baseUrl() ?>/register">إنشاء حساب جديد</a></p>
            </div>
        </div>
    </div>
</div> 