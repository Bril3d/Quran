<?php 
// Set page title for layout
$pageTitle = 'إنشاء حساب جديد';
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>إنشاء حساب جديد</h2>
                <p>سجل الآن للاستفادة من جميع مميزات الموقع</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <form class="auth-form" action="<?= baseUrl() ?>/register" method="POST">
                <div class="form-group">
                    <label for="name">الاسم</label>
                    <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="name" name="name" value="<?= isset($name) ? $name : '' ?>" required>
                    <?php if(isset($errors['name'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['name'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">البريد الإلكتروني</label>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= isset($email) ? $email : '' ?>" required>
                    <?php if(isset($errors['email'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['email'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">كلمة المرور</label>
                    <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                    <?php if(isset($errors['password'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['password'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" required>
                    <?php if(isset($errors['confirm_password'])): ?>
                        <div class="invalid-feedback">
                            <?= $errors['confirm_password'] ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                    <label class="form-check-label" for="terms">أوافق على <a href="#">شروط الاستخدام</a> و <a href="#">سياسة الخصوصية</a></label>
                </div>
                
                <button type="submit" class="btn btn-primary">إنشاء حساب</button>
            </form>
            
            <div class="auth-links">
                <p>لديك حساب بالفعل؟ <a href="<?= baseUrl() ?>/login">تسجيل الدخول</a></p>
            </div>
        </div>
    </div>
</div> 