<div class="container settings-page my-5">
    <div class="row">
        <!-- Settings Sidebar -->
        <div class="col-md-4">
            <div class="card profile-sidebar mb-4">
                <div class="card-body text-center">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle fa-5x"></i>
                    </div>
                    <h4 class="mt-3"><?= htmlspecialchars($user['name']) ?></h4>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <div class="mt-3">
                        <a href="<?= baseUrl() ?>/profile" class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-user me-1"></i> الملف الشخصي
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Settings Navigation -->
            <div class="list-group settings-nav mb-4">
                <a href="#app-settings" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="fas fa-cog me-2"></i> إعدادات التطبيق
                </a>
                <a href="#quran-settings" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-book me-2"></i> إعدادات القرآن
                </a>
                <a href="#audio-settings" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-volume-up me-2"></i> إعدادات الصوت
                </a>
                <a href="#notification-settings" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="fas fa-bell me-2"></i> إعدادات الإشعارات
                </a>
            </div>
        </div>
        
        <!-- Settings Content -->
        <div class="col-md-8">
            <!-- Flash Message -->
            <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    تم تحديث الإعدادات بنجاح!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= $error ?>
                </div>
            <?php endif; ?>
            
            <!-- Settings Tabs -->
            <div class="tab-content">
                <!-- App Settings -->
                <div class="tab-pane fade show active" id="app-settings">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i> إعدادات التطبيق</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= baseUrl() ?>/settings/update" method="post">
                                <div class="mb-3">
                                    <label class="form-label">المظهر</label>
                                    <div class="d-flex">
                                        <div class="form-check form-check-inline me-4">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-light" value="light" <?= (!isset($preferences['theme']) || $preferences['theme'] == 'light') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="theme-light">
                                                <i class="fas fa-sun me-1"></i> فاتح
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline me-4">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-dark" value="dark" <?= (isset($preferences['theme']) && $preferences['theme'] == 'dark') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="theme-dark">
                                                <i class="fas fa-moon me-1"></i> داكن
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="theme" id="theme-auto" value="auto" <?= (isset($preferences['theme']) && $preferences['theme'] == 'auto') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="theme-auto">
                                                <i class="fas fa-magic me-1"></i> تلقائي
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Quran Settings -->
                <div class="tab-pane fade" id="quran-settings">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0"><i class="fas fa-book me-2"></i> إعدادات القرآن</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= baseUrl() ?>/settings/update" method="post">
                                <div class="mb-3">
                                    <label for="quran-font" class="form-label">خط القرآن</label>
                                    <select class="form-select" id="quran-font" name="quran_font">
                                        <option value="Amiri" <?= (!isset($preferences['quran_font']) || $preferences['quran_font'] == 'Amiri') ? 'selected' : '' ?>>أميري</option>
                                        <option value="Scheherazade" <?= (isset($preferences['quran_font']) && $preferences['quran_font'] == 'Scheherazade') ? 'selected' : '' ?>>شهرزاد</option>
                                        <option value="NotoNaskh" <?= (isset($preferences['quran_font']) && $preferences['quran_font'] == 'NotoNaskh') ? 'selected' : '' ?>>نوتو نسخ</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="quran-font-size" class="form-label">حجم الخط</label>
                                    <div class="d-flex align-items-center">
                                        <input type="range" class="form-range w-75" id="quran-font-size" name="quran_font_size" min="16" max="32" step="2" value="<?= isset($preferences['quran_font_size']) ? $preferences['quran_font_size'] : '22' ?>">
                                        <span class="font-size-value ms-3" id="font-size-value"><?= isset($preferences['quran_font_size']) ? $preferences['quran_font_size'] : '22' ?>px</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">عرض الترجمة</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="translation-display" name="translation_display" <?= (isset($preferences['translation_display']) && $preferences['translation_display']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="translation-display">عرض الترجمة مع الآيات</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">عرض التفسير</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="tafsir-display" name="tafsir_display" <?= (isset($preferences['tafsir_display']) && $preferences['tafsir_display']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tafsir-display">عرض التفسير مع الآيات</label>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Audio Settings -->
                <div class="tab-pane fade" id="audio-settings">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0"><i class="fas fa-volume-up me-2"></i> إعدادات الصوت</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= baseUrl() ?>/settings/update" method="post">
                                <div class="mb-3">
                                    <label for="reciter" class="form-label">القارئ المفضل</label>
                                    <select class="form-select" id="reciter" name="reciter_id">
                                        <option value="1" <?= (!isset($preferences['reciter_id']) || $preferences['reciter_id'] == 1) ? 'selected' : '' ?>>مشاري راشد العفاسي</option>
                                        <option value="2" <?= (isset($preferences['reciter_id']) && $preferences['reciter_id'] == 2) ? 'selected' : '' ?>>عبد الباسط عبد الصمد</option>
                                        <option value="3" <?= (isset($preferences['reciter_id']) && $preferences['reciter_id'] == 3) ? 'selected' : '' ?>>سعد الغامدي</option>
                                        <option value="4" <?= (isset($preferences['reciter_id']) && $preferences['reciter_id'] == 4) ? 'selected' : '' ?>>ماهر المعيقلي</option>
                                        <option value="5" <?= (isset($preferences['reciter_id']) && $preferences['reciter_id'] == 5) ? 'selected' : '' ?>>محمود خليل الحصري</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">تشغيل تلقائي</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="auto-play" name="auto_play_audio" <?= (isset($preferences['auto_play_audio']) && $preferences['auto_play_audio']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="auto-play">تشغيل الصوت تلقائيًا عند فتح السورة</label>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Settings -->
                <div class="tab-pane fade" id="notification-settings">
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <h5 class="mb-0"><i class="fas fa-bell me-2"></i> إعدادات الإشعارات</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= baseUrl() ?>/settings/update" method="post">
                                <div class="mb-3">
                                    <label class="form-label">إشعارات البريد الإلكتروني</label>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email-daily-verse" name="notification_daily_verse" <?= (isset($preferences['notification_daily_verse']) && $preferences['notification_daily_verse']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email-daily-verse">آية اليوم</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="email-prayer-times" name="notification_prayer_times" <?= (isset($preferences['notification_prayer_times']) && $preferences['notification_prayer_times']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email-prayer-times">مواقيت الصلاة</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="email-newsletters" name="notification_newsletters" <?= (isset($preferences['notification_newsletters']) && $preferences['notification_newsletters']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="email-newsletters">النشرة الإخبارية</label>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">إشعارات الموقع</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="browser-notifications" name="browser_notifications" <?= (isset($preferences['browser_notifications']) && $preferences['browser_notifications']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="browser-notifications">تفعيل إشعارات المتصفح</label>
                                    </div>
                                </div>
                                
                                <hr class="my-4">
                                
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">حفظ الإعدادات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update font size value display
    document.getElementById('quran-font-size').addEventListener('input', function() {
        document.getElementById('font-size-value').textContent = this.value + 'px';
    });
    
    // Save active tab state
    document.querySelectorAll('.settings-nav .list-group-item').forEach(function(tab) {
        tab.addEventListener('click', function() {
            localStorage.setItem('activeSettingsTab', this.getAttribute('href'));
        });
    });
    
    // Restore active tab on page load
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = localStorage.getItem('activeSettingsTab');
        if (activeTab) {
            const tab = document.querySelector('.settings-nav .list-group-item[href="' + activeTab + '"]');
            if (tab) {
                const tabTrigger = new bootstrap.Tab(tab);
                tabTrigger.show();
            }
        }
    });
</script> 