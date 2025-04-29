<?php
// Set page title for layout
$pageTitle = 'مواقيت الصلاة';
?>

<div class="prayer-page">
    <div class="container">
        <div class="section-header text-center">
            <h2>مواقيت الصلاة</h2>
            <div class="location-info">
                <i class="fas fa-map-marker-alt"></i>
                <span id="current-location">
                    <?= isset($city) ? $city : 'مكة المكرمة' ?>، 
                    <?= isset($country) ? $country : 'المملكة العربية السعودية' ?>
                </span>
                <button class="btn btn-sm btn-link change-location-btn" data-bs-toggle="modal" data-bs-target="#locationModal">
                    تغيير
                </button>
            </div>
        </div>
        
        <!-- Today's Prayer Times -->
        <div class="today-prayer-times">
            <div class="date-display">
                <div class="row">
                    <div class="col-md-6">
                        <div class="gregorian-date">
                            <i class="far fa-calendar-alt"></i>
                            <span><?= $todayPrayerTimes['date'] ?? date('d M Y') ?></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hijri-date text-md-end">
                            <i class="far fa-calendar"></i>
                            <span><?= $todayPrayerTimes['hijri'] ?? '' ?></span>
                            <span class="hijri-month"><?= $todayPrayerTimes['hijriMonth'] ?? '' ?></span>
                        </div>
                    </div>
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
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Fajr'] ?? '05:00' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="prayer-time-card sunrise">
                            <div class="prayer-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <div class="prayer-name">الشروق</div>
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Sunrise'] ?? '06:15' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="prayer-time-card dhuhr">
                            <div class="prayer-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <div class="prayer-name">الظهر</div>
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Dhuhr'] ?? '12:00' ?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="prayer-time-card asr">
                            <div class="prayer-icon">
                                <i class="fas fa-sun"></i>
                            </div>
                            <div class="prayer-name">العصر</div>
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Asr'] ?? '15:30' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="prayer-time-card maghrib">
                            <div class="prayer-icon">
                                <i class="fas fa-moon"></i>
                            </div>
                            <div class="prayer-name">المغرب</div>
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Maghrib'] ?? '18:15' ?></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="prayer-time-card isha">
                            <div class="prayer-icon">
                                <i class="fas fa-moon"></i>
                            </div>
                            <div class="prayer-name">العشاء</div>
                            <div class="prayer-time"><?= $todayPrayerTimes['timings']['Isha'] ?? '19:30' ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Monthly Prayer Times -->
        <div class="monthly-prayer-times mt-5">
            <div class="section-header">
                <h3>مواقيت الصلاة لهذا الشهر</h3>
            </div>
            
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>اليوم</th>
                            <th>الفجر</th>
                            <th>الشروق</th>
                            <th>الظهر</th>
                            <th>العصر</th>
                            <th>المغرب</th>
                            <th>العشاء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($monthPrayerTimes) && !empty($monthPrayerTimes)): ?>
                            <?php foreach($monthPrayerTimes as $day): ?>
                                <tr>
                                    <td>
                                        <?= $day['date']['gregorian']['day'] ?> 
                                        <?= $day['date']['gregorian']['month']['en'] ?>
                                        <small class="text-muted d-block"><?= $day['date']['hijri']['day'] ?> <?= $day['date']['hijri']['month']['ar'] ?></small>
                                    </td>
                                    <td><?= $day['timings']['Fajr'] ?></td>
                                    <td><?= $day['timings']['Sunrise'] ?></td>
                                    <td><?= $day['timings']['Dhuhr'] ?></td>
                                    <td><?= $day['timings']['Asr'] ?></td>
                                    <td><?= $day['timings']['Maghrib'] ?></td>
                                    <td><?= $day['timings']['Isha'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">لا توجد بيانات متاحة</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Change Location Modal -->
        <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="locationModalLabel">تغيير الموقع</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="locationForm">
                            <div class="mb-3">
                                <label for="city" class="form-label">المدينة</label>
                                <input type="text" class="form-control" id="city" name="city" value="<?= $city ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="country" class="form-label">الدولة</label>
                                <input type="text" class="form-control" id="country" name="country" value="<?= $country ?>">
                            </div>
                            <div class="text-center">
                                <span>أو</span>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-primary w-100" id="detectLocation">
                                    <i class="fas fa-location-arrow"></i> استخدام موقعي الحالي
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-primary" id="saveLocation">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Set base URL for API calls
    const baseUrl = "<?= baseUrl() ?>";
    
    document.addEventListener('DOMContentLoaded', function() {
        // Detect user's location
        const detectLocationBtn = document.getElementById('detectLocation');
        detectLocationBtn.addEventListener('click', function() {
            if (navigator.geolocation) {
                detectLocationBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري تحديد الموقع...';
                detectLocationBtn.disabled = true;
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;
                    
                    // Reverse geocoding to get city and country
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=10&accept-language=ar`)
                        .then(response => response.json())
                        .then(data => {
                            const city = data.address.city || data.address.town || data.address.village || '';
                            const country = data.address.country || '';
                            
                            document.getElementById('city').value = city;
                            document.getElementById('country').value = country;
                            
                            // Store coordinates in hidden fields
                            document.cookie = `latitude=${latitude}; path=/; max-age=31536000`;
                            document.cookie = `longitude=${longitude}; path=/; max-age=31536000`;
                            
                            detectLocationBtn.innerHTML = '<i class="fas fa-check"></i> تم تحديد الموقع';
                            setTimeout(() => {
                                detectLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> استخدام موقعي الحالي';
                                detectLocationBtn.disabled = false;
                            }, 2000);
                        })
                        .catch(error => {
                            console.error('Error getting location details:', error);
                            detectLocationBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> تعذر تحديد الموقع';
                            setTimeout(() => {
                                detectLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> استخدام موقعي الحالي';
                                detectLocationBtn.disabled = false;
                            }, 2000);
                        });
                }, function(error) {
                    console.error('Error getting location:', error);
                    detectLocationBtn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> تعذر تحديد الموقع';
                    setTimeout(() => {
                        detectLocationBtn.innerHTML = '<i class="fas fa-location-arrow"></i> استخدام موقعي الحالي';
                        detectLocationBtn.disabled = false;
                    }, 2000);
                });
            } else {
                alert('متصفحك لا يدعم تحديد الموقع');
            }
        });
        
        // Save location
        document.getElementById('saveLocation').addEventListener('click', function() {
            const city = document.getElementById('city').value;
            const country = document.getElementById('country').value;
            
            if (!city) {
                alert('الرجاء إدخال المدينة');
                return;
            }
            
            // Save to cookies
            document.cookie = `city=${city}; path=/; max-age=31536000`;
            document.cookie = `country=${country}; path=/; max-age=31536000`;
            
            // Update the displayed location
            document.getElementById('current-location').textContent = `${city}، ${country}`;
            
            // Get prayer times for the new location
            fetch(`${baseUrl}/api/prayer-times/by-city`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `city=${city}&country=${country}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page to show new prayer times
                    window.location.reload();
                } else {
                    alert('تعذر الحصول على مواقيت الصلاة للموقع المحدد');
                }
            })
            .catch(error => {
                console.error('Error fetching prayer times:', error);
                alert('حدث خطأ أثناء جلب مواقيت الصلاة');
            });
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('locationModal'));
            modal.hide();
        });
    });
</script>

<style>
    .prayer-page {
        padding: 30px 0;
    }
    
    .location-info {
        margin-top: 15px;
        color: #777;
    }
    
    .dark-theme .location-info {
        color: #aaa;
    }
    
    .location-info i {
        color: #0d6efd;
        margin-left: 5px;
    }
    
    .dark-theme .location-info i {
        color: #77a7ff;
    }
    
    .change-location-btn {
        padding: 0;
        font-size: 0.8rem;
        vertical-align: middle;
    }
    
    .today-prayer-times {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .dark-theme .today-prayer-times {
        background-color: #2a2a2a;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .date-display {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    
    .dark-theme .date-display {
        border-bottom: 1px solid #444;
    }
    
    .gregorian-date, .hijri-date {
        font-size: 1.1rem;
    }
    
    .hijri-month {
        margin-right: 5px;
        color: #0d6efd;
    }
    
    .dark-theme .hijri-month {
        color: #77a7ff;
    }
    
    .gregorian-date i, .hijri-date i {
        margin-left: 10px;
        color: #0d6efd;
    }
    
    .dark-theme .gregorian-date i, .dark-theme .hijri-date i {
        color: #77a7ff;
    }
    
    .prayer-time-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
    }
    
    .dark-theme .prayer-time-card {
        background-color: #333;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .prayer-time-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .dark-theme .prayer-time-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .prayer-time-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background-color: #0d6efd;
    }
    
    .dark-theme .prayer-time-card::before {
        background-color: #77a7ff;
    }
    
    .prayer-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #0d6efd;
    }
    
    .dark-theme .prayer-icon {
        color: #77a7ff;
    }
    
    .prayer-name {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .prayer-time {
        font-size: 1.5rem;
        font-weight: 300;
    }
    
    /* Prayer time cards custom colors */
    .prayer-time-card.fajr::before { background-color: #6610f2; }
    .prayer-time-card.sunrise::before { background-color: #fd7e14; }
    .prayer-time-card.dhuhr::before { background-color: #ffc107; }
    .prayer-time-card.asr::before { background-color: #20c997; }
    .prayer-time-card.maghrib::before { background-color: #0dcaf0; }
    .prayer-time-card.isha::before { background-color: #6f42c1; }
    
    .prayer-time-card.fajr .prayer-icon { color: #6610f2; }
    .prayer-time-card.sunrise .prayer-icon { color: #fd7e14; }
    .prayer-time-card.dhuhr .prayer-icon { color: #ffc107; }
    .prayer-time-card.asr .prayer-icon { color: #20c997; }
    .prayer-time-card.maghrib .prayer-icon { color: #0dcaf0; }
    .prayer-time-card.isha .prayer-icon { color: #6f42c1; }
    
    .dark-theme .prayer-time-card.fajr::before { background-color: #8540f5; }
    .dark-theme .prayer-time-card.sunrise::before { background-color: #fd9843; }
    .dark-theme .prayer-time-card.dhuhr::before { background-color: #ffcd39; }
    .dark-theme .prayer-time-card.asr::before { background-color: #3dd5ac; }
    .dark-theme .prayer-time-card.maghrib::before { background-color: #3dd5f3; }
    .dark-theme .prayer-time-card.isha::before { background-color: #8a63d2; }
    
    .dark-theme .prayer-time-card.fajr .prayer-icon { color: #8540f5; }
    .dark-theme .prayer-time-card.sunrise .prayer-icon { color: #fd9843; }
    .dark-theme .prayer-time-card.dhuhr .prayer-icon { color: #ffcd39; }
    .dark-theme .prayer-time-card.asr .prayer-icon { color: #3dd5ac; }
    .dark-theme .prayer-time-card.maghrib .prayer-icon { color: #3dd5f3; }
    .dark-theme .prayer-time-card.isha .prayer-icon { color: #8a63d2; }
    
    .monthly-prayer-times {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .dark-theme .monthly-prayer-times {
        background-color: #2a2a2a;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .table {
        font-size: 0.9rem;
    }
    
    .dark-theme .table {
        color: #f5f5f5;
    }
    
    @media (max-width: 768px) {
        .prayer-time-card {
            padding: 15px 10px;
        }
        
        .prayer-name {
            font-size: 1rem;
        }
        
        .prayer-time {
            font-size: 1.2rem;
        }
    }
</style> 