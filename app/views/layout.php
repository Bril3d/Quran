<!DOCTYPE html>
<html lang="<?= DEFAULT_LANGUAGE ?>" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= asset('img/favicon.png') ?>" type="image/png">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.1/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <!-- Google Fonts - Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Naskh+Arabic:wght@400;500;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Theme Color for Mobile -->
    <meta name="theme-color" content="#07914a">
    
    <?php if(isset($extraStyles)): ?>
        <?= $extraStyles ?>
    <?php endif; ?>
</head>
<body>
    <!-- Header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="<?= baseUrl() ?>">
                    <img src="<?= asset('assets/quran.png') ?>" alt="<?= APP_NAME ?>" class="logo">
                   
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>">الرئيسية</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>/quran">القرآن الكريم</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>/audio">الاستماع</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>/prayer-times">مواقيت الصلاة</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= baseUrl() ?>/chat">استشارة قرآنية</a>
                        </li>
                    </ul>
                    
                    <div class="d-flex">
                        <button class="btn btn-sm theme-toggle ms-2" title="تغيير المظهر">
                            <i class="fas fa-moon dark-icon"></i>
                            <i class="fas fa-sun light-icon"></i>
                        </button>
                        
                        <?php if(isLoggedIn()): ?>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> <?= $_SESSION['user_name'] ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>/profile">الملف الشخصي</a></li>
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>/settings">الإعدادات</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="<?= baseUrl() ?>/logout">تسجيل الخروج</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= baseUrl() ?>/login" class="btn btn-sm btn-outline-primary ms-2">تسجيل الدخول</a>
                            <a href="<?= baseUrl() ?>/register" class="btn btn-sm btn-primary">إنشاء حساب</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Main Content -->
    <main class="site-content">
        <?php if(isset($content_view)): ?>
            <?php include(BASE_PATH . '/app/views/' . $content_view . '.php'); ?>
        <?php endif; ?>
    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>عن الموقع</h5>
                    <p>موقع القرآن الكريم يهدف إلى تقديم تجربة مميزة لقراءة واستماع القرآن الكريم، مع ميزات فريدة مثل التوصيات المزاجية والاستشارات القرآنية.</p>
                </div>
                <div class="col-md-4">
                    <h5>روابط سريعة</h5>
                    <ul class="footer-links">
                        <li><a href="<?= baseUrl() ?>/quran">القرآن الكريم</a></li>
                        <li><a href="<?= baseUrl() ?>/audio">الاستماع</a></li>
                        <li><a href="<?= baseUrl() ?>/prayer-times">مواقيت الصلاة</a></li>
                        <li><a href="<?= baseUrl() ?>/chat">استشارة قرآنية</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>تواصل معنا</h5>
                    <ul class="social-links">
                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                    </ul>
                    <p>البريد الإلكتروني: <a href="mailto:info@quranwebsite.com">info@quranwebsite.com</a></p>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?= date('Y') ?> <?= APP_NAME ?>. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.min.js"></script>
    <script src="<?= asset('js/main.js') ?>"></script>
    
    <?php if(isset($extraScripts)): ?>
        <?= $extraScripts ?>
    <?php endif; ?>
    
    <script>
        // Apply initial theme based on system preference or cookie
        (function() {
            const body = document.body;
            const prefersColorSchemeDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const cookieTheme = getCookie('theme');
            
            // First check the cookie (user's explicit choice)
            if (cookieTheme) {
                body.classList.add(cookieTheme === 'dark' ? 'dark-theme' : 'light-theme');
                body.classList.remove(cookieTheme === 'dark' ? 'light-theme' : 'dark-theme');
            } 
            // If no cookie, use system preference
            else if (prefersColorSchemeDark) {
                body.classList.add('dark-theme');
                body.classList.remove('light-theme');
                document.cookie = "theme=dark; path=/; max-age=31536000";
            } else {
                body.classList.add('light-theme');
                body.classList.remove('dark-theme');
                document.cookie = "theme=light; path=/; max-age=31536000";
            }
        })();
        
        // Theme toggle functionality
        document.querySelector('.theme-toggle').addEventListener('click', function() {
            const body = document.body;
            const isDarkTheme = body.classList.contains('dark-theme');
            
            if (isDarkTheme) {
                body.classList.remove('dark-theme');
                body.classList.add('light-theme');
                document.cookie = "theme=light; path=/; max-age=31536000";
            } else {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                document.cookie = "theme=dark; path=/; max-age=31536000";
            }
        });
        
        // Helper function to get cookie value
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }
    </script>
</body>
</html> 