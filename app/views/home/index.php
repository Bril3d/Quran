<?php 
// Set page title for layout
$pageTitle = 'الرئيسية';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4">القرآن الكريم</h1>
                    <p class="lead">إِنَّا نَحْنُ نَزَّلْنَا الذِّكْرَ وَإِنَّا لَهُ لَحَافِظُونَ</p>
                    <p class="hero-description">أول موقع عربي يقدم لك تجربة قراءة واستماع مع توصيات قرآنية خاصة للحالة المزاجية</p>
                    <div class="hero-buttons">
                        <a href="<?= baseUrl() ?>/quran" class="btn btn-primary btn-lg">ابدأ القراءة</a>
                        <a href="<?= baseUrl() ?>/audio" class="btn btn-outline-primary btn-lg ms-2">استمع الآن</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <img src="<?= asset('img/quran-hero.png') ?>" alt="القرآن الكريم" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Times Section -->
<section class="prayer-times-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>مواقيت الصلاة</h2>
            <div class="location-info">
                <i class="fas fa-map-marker-alt"></i>
                <span id="current-location">
                    <?= isset($_COOKIE['city']) ? $_COOKIE['city'] : 'مكة المكرمة' ?>، 
                    <?= isset($_COOKIE['country']) ? $_COOKIE['country'] : 'المملكة العربية السعودية' ?>
                </span>
                <button class="btn btn-sm btn-link" id="change-location-btn" data-bs-toggle="modal" data-bs-target="#locationModal">
                    تغيير
                </button>
            </div>
        </div>
        
        <div class="prayer-times-container">
            <div class="row">
                <div class="col-md-4">
                    <div class="prayer-time-card fajr">
                        <div class="prayer-icon">
                            <i class="fas fa-sun"></i>
                        </div>
                        <div class="prayer-name">الفجر</div>
                        <div class="prayer-time"><?= $prayerTimes['timings']['Fajr'] ?? '05:00' ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="prayer-time-card dhuhr">
                        <div class="prayer-icon">
                            <i class="fas fa-sun"></i>
                        </div>
                        <div class="prayer-name">الظهر</div>
                        <div class="prayer-time"><?= $prayerTimes['timings']['Dhuhr'] ?? '12:00' ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="prayer-time-card asr">
                        <div class="prayer-icon">
                            <i class="fas fa-sun"></i>
                        </div>
                        <div class="prayer-name">العصر</div>
                        <div class="prayer-time"><?= $prayerTimes['timings']['Asr'] ?? '15:30' ?></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="prayer-time-card maghrib">
                        <div class="prayer-icon">
                            <i class="fas fa-moon"></i>
                        </div>
                        <div class="prayer-name">المغرب</div>
                        <div class="prayer-time"><?= $prayerTimes['timings']['Maghrib'] ?? '18:00' ?></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="prayer-time-card isha">
                        <div class="prayer-icon">
                            <i class="fas fa-moon"></i>
                        </div>
                        <div class="prayer-name">العشاء</div>
                        <div class="prayer-time"><?= $prayerTimes['timings']['Isha'] ?? '19:30' ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="<?= baseUrl() ?>/prayer-times" class="btn btn-outline-primary">عرض المزيد</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>مميزات الموقع</h2>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>قراءة القرآن</h3>
                    <p>اقرأ القرآن الكريم كاملاً مع خيارات عرض مختلفة وإمكانية التبديل بين التفسير والترجمة.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <h3>الاستماع للتلاوات</h3>
                    <p>استمع إلى تلاوات المصاحف المرتلة بأصوات عدة قراء مشهورين مع إمكانية التحميل.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>استشارة قرآنية</h3>
                    <p>ميزة فريدة لتوصيات السور القرآنية بناءً على حالتك المزاجية ومشاعرك.</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>مواقيت الصلاة</h3>
                    <p>احصل على مواقيت الصلاة الدقيقة لمنطقتك مع إمكانية عرض المواقيت لأي مدينة حول العالم.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bookmark"></i>
                    </div>
                    <h3>حفظ العلامات</h3>
                    <p>خاصية حفظ العلامات المرجعية والآيات المفضلة للعودة إليها لاحقاً بسهولة.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-moon"></i>
                    </div>
                    <h3>الوضع الليلي</h3>
                    <p>قراءة مريحة في الأوقات المختلفة مع إمكانية التبديل بين الوضع الفاتح والوضع الداكن.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Surah of the Day -->
<section class="surah-of-day-section">
    <div class="container">
        <div class="section-header text-center">
            <h2>سورة اليوم</h2>
        </div>
        
        <div class="surah-card">
            <div class="row">
                <div class="col-md-4">
                    <div class="surah-info">
                        <div class="surah-number"><?= $randomSurah['number'] ?></div>
                        <h3 class="surah-name"><?= $randomSurah['name'] ?></h3>
                        <p class="surah-english-name"><?= $randomSurah['englishName'] ?></p>
                        <p class="surah-english-translation"><?= $randomSurah['englishNameTranslation'] ?></p>
                        <p class="ayah-count">عدد الآيات: <?= $randomSurah['numberOfAyahs'] ?></p>
                        <div class="surah-actions">
                            <a href="<?= baseUrl() ?>/quran?surah=<?= $randomSurah['number'] ?>" class="btn btn-primary">
                                قراءة السورة
                            </a>
                            <a href="<?= baseUrl() ?>/audio?surah=<?= $randomSurah['number'] ?>" class="btn btn-outline-primary ms-2">
                                استماع
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="surah-decoration">
                        <div class="bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
                        <div class="decorative-frame">
                            <img src="<?= asset('img/quran-frame.png') ?>" alt="إطار قرآني" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta-section">
    <div class="container text-center">
        <h2>انضم إلينا اليوم</h2>
        <p>سجل الآن للاستفادة من كافة خدمات الموقع وحفظ تقدمك ومفضلاتك</p>
        <div class="cta-buttons">
            <?php if(isLoggedIn()): ?>
                <a href="<?= baseUrl() ?>/quran" class="btn btn-primary btn-lg">ابدأ القراءة</a>
            <?php else: ?>
                <a href="<?= baseUrl() ?>/register" class="btn btn-primary btn-lg">سجل الآن</a>
                <a href="<?= baseUrl() ?>/login" class="btn btn-outline-primary btn-lg ms-2">تسجيل الدخول</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Location Modal -->
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">تحديد الموقع</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="locationForm">
                    <div class="mb-3">
                        <label for="city" class="form-label">المدينة</label>
                        <input type="text" class="form-control" id="city" placeholder="أدخل اسم المدينة" required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">الدولة</label>
                        <input type="text" class="form-control" id="country" placeholder="أدخل اسم الدولة" required>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" id="use-geolocation">استخدام موقعي الحالي</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="save-location">حفظ</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Get user location
    document.getElementById('use-geolocation').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                // In a real app, we would convert coordinates to city/country using a geocoding API
                // For now, just save the coordinates
                document.cookie = "latitude=" + position.coords.latitude + "; path=/; max-age=31536000";
                document.cookie = "longitude=" + position.coords.longitude + "; path=/; max-age=31536000";
                
                // Refresh the page to load new prayer times
                location.reload();
            }, function(error) {
                alert('خطأ في تحديد الموقع: ' + error.message);
            });
        } else {
            alert('متصفحك لا يدعم تحديد الموقع الجغرافي.');
        }
    });
    
    // Save manual location
    document.getElementById('save-location').addEventListener('click', function() {
        const city = document.getElementById('city').value;
        const country = document.getElementById('country').value;
        
        if (city && country) {
            document.cookie = "city=" + city + "; path=/; max-age=31536000";
            document.cookie = "country=" + country + "; path=/; max-age=31536000";
            
            // In a real app, we would convert city/country to coordinates using a geocoding API
            // For now, we'll just close the modal and reload
            $('#locationModal').modal('hide');
            location.reload();
        } else {
            alert('الرجاء إدخال المدينة والدولة');
        }
    });
</script> 