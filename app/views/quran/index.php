<?php 
// Set page title for layout
$pageTitle = 'القرآن الكريم';
?>

<div class="quran-container">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="surah-list-container">
                    <div class="card">
                        <div class="card-header">
                            <h3>السور</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="surah-list">
                                <?php if(isset($surahs) && !empty($surahs)): ?>
                                    <?php foreach($surahs as $s): ?>
                                        <a href="<?= baseUrl() ?>/quran?surah=<?= $s['number'] ?>" class="surah-list-item <?= (isset($surah) && $surah['number'] == $s['number']) ? 'active' : '' ?>">
                                            <div class="surah-number"><?= $s['number'] ?></div>
                                            <div class="surah-name">
                                                <span class="arabic-name"><?= $s['name'] ?></span>
                                                <span class="english-name"><?= $s['englishName'] ?></span>
                                            </div>
                                            <div class="surah-ayah-count"><?= $s['numberOfAyahs'] ?></div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-9">
                <?php if(isset($surah) && $surah): ?>
                    <div class="quran-reader-container">
                        <div class="surah-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h2 class="surah-title">
                                        سورة <?= $surah['name'] ?>
                                        <span class="surah-title-en"><?= $surah['englishName'] ?></span>
                                    </h2>
                                    <div class="surah-info">
                                        <span><?= $surah['numberOfAyahs'] ?> آية</span>
                                        <?php if(isset($surah['revelationType'])): ?>
                                            <span class="revelation-type"><?= ($surah['revelationType'] == 'Meccan') ? 'مكية' : 'مدنية' ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="surah-actions">
                                        <?php if(isLoggedIn()): ?>
                                            <button class="btn btn-sm btn-outline-primary add-bookmark" data-surah="<?= $surah['number'] ?>" data-ayah="1">
                                                <i class="fas fa-bookmark"></i> إضافة إلى المفضلة
                                            </button>
                                        <?php endif; ?>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-volume-up"></i> استماع
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php foreach($reciters as $id => $name): ?>
                                                    <li><a class="dropdown-item listen-reciter" href="#" data-reciter="<?= $id ?>" data-surah="<?= $surah['number'] ?>"><?= $name ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($surah['number'] != 9): ?>
                            <div class="bismillah text-center my-4">
                                بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                            </div>
                        <?php endif; ?>
                        
                        <div class="quran-text">
                            <?php if(isset($surah['ayahs']) && !empty($surah['ayahs'])): ?>
                                <?php foreach($surah['ayahs'] as $ayah): ?>
                                    <div id="ayah-<?= $ayah['numberInSurah'] ?>" class="verse <?= (isset($currentAyah) && $currentAyah == $ayah['numberInSurah']) ? 'active' : '' ?>">
                                        <span class="verse-text"><?= $ayah['text'] ?></span>
                                        <span class="verse-number"><?= $ayah['numberInSurah'] ?></span>
                                        <div class="verse-actions">
                                            <button class="verse-audio" data-surah="<?= $surah['number'] ?>" data-ayah="<?= $ayah['numberInSurah'] ?>">
                                                <i class="fas fa-play"></i>
                                            </button>
                                            <?php if(isLoggedIn()): ?>
                                                <button class="verse-bookmark" data-surah="<?= $surah['number'] ?>" data-ayah="<?= $ayah['numberInSurah'] ?>">
                                                    <i class="fas fa-bookmark"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <h4 class="alert-heading">لا يمكن تحميل الآيات حاليًا</h4>
                                    <p>تعذر الاتصال بخدمة القرآن الكريم. يرجى المحاولة مرة أخرى لاحقًا.</p>
                                    <hr>
                                    <p class="mb-0">
                                        يمكنك المحاولة مرة أخرى بالضغط على 
                                        <a href="javascript:location.reload();" class="alert-link">تحديث الصفحة</a>
                                        أو العودة إلى <a href="<?= baseUrl() ?>" class="alert-link">الصفحة الرئيسية</a>.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="surah-navigation my-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <?php if($surah['number'] > 1): ?>
                                        <a href="<?= baseUrl() ?>/quran?surah=<?= $surah['number']-1 ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-right"></i> السورة السابقة
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 text-center">
                                    <a href="#" class="btn btn-outline-primary" id="scroll-to-top">
                                        <i class="fas fa-arrow-up"></i> العودة للأعلى
                                    </a>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if($surah['number'] < 114): ?>
                                        <a href="<?= baseUrl() ?>/quran?surah=<?= $surah['number']+1 ?>" class="btn btn-outline-secondary">
                                            السورة التالية <i class="fas fa-arrow-left"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">لا يمكن تحميل السورة حاليًا</h4>
                        <p>تعذر الاتصال بخدمة القرآن الكريم. يرجى المحاولة مرة أخرى لاحقًا.</p>
                        <hr>
                        <p class="mb-0">
                            يمكنك المحاولة مرة أخرى بالضغط على 
                            <a href="javascript:location.reload();" class="alert-link">تحديث الصفحة</a>
                            أو العودة إلى <a href="<?= baseUrl() ?>" class="alert-link">الصفحة الرئيسية</a>.
                        </p>
                    </div>
                <?php endif; ?>
                
                <?php if(isLoggedIn() && !empty($bookmarks)): ?>
                    <div class="bookmarks-container mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h3>المفضلة</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <?php foreach($bookmarks as $bookmark): ?>
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="bookmark-item">
                                                <a href="<?= baseUrl() ?>/quran?surah=<?= $bookmark['surah_number'] ?>&ayah=<?= $bookmark['ayah_number'] ?>">
                                                    <div class="bookmark-surah"><?= $bookmark['name'] ? $bookmark['name'] : 'سورة ' . $bookmark['surah_number'] ?></div>
                                                    <div class="bookmark-location">الآية <?= $bookmark['ayah_number'] ?></div>
                                                </a>
                                                <button class="btn btn-sm btn-danger remove-bookmark" data-bookmark-id="<?= $bookmark['id'] ?>">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="audio-player-wrapper d-none">
    <div class="audio-player">
        <div class="audio-player-info">
            <span class="audio-surah-name"></span>
            <span class="audio-ayah-number"></span>
        </div>
        <div class="audio-player-controls">
            <button class="audio-player-btn play-pause">
                <i class="fas fa-play"></i>
                <i class="fas fa-pause"></i>
            </button>
            <div class="audio-progress">
                <div class="audio-progress-bar"></div>
            </div>
            <div class="audio-time">
                <span class="audio-current-time">0:00</span>
                <span class="audio-duration">0:00</span>
            </div>
            <button class="audio-player-btn close-player">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <audio id="quran-audio" src="" data-surah="<?= $surah['number'] ?>" data-reciter="ar.alafasy"></audio>
    </div>
</div>

<script>
    // Set base URL for API calls
    const baseUrl = "<?= baseUrl() ?>";
    
    // Initialize the page functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Audio player for individual ayahs
        const verses = document.querySelectorAll('.verse-audio');
        const audioPlayer = document.getElementById('quran-audio');
        const audioWrapper = document.querySelector('.audio-player-wrapper');
        const playPauseBtn = document.querySelector('.play-pause');
        const closePlayerBtn = document.querySelector('.close-player');
        const progressBar = document.querySelector('.audio-progress-bar');
        const currentTimeEl = document.querySelector('.audio-current-time');
        const durationEl = document.querySelector('.audio-duration');
        const surahNameEl = document.querySelector('.audio-surah-name');
        const ayahNumberEl = document.querySelector('.audio-ayah-number');
        
        // Ayah audio player
        verses.forEach(verse => {
            verse.addEventListener('click', function() {
                const surah = this.dataset.surah;
                const ayah = this.dataset.ayah;
                
                // Fetch the audio URL
                fetch(`${baseUrl}/api/quran/ayah-audio?surah=${surah}&ayah=${ayah}&reciter=ar.alafasy`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.data && data.data.audio) {
                            // Set audio source
                            audioPlayer.src = data.data.audio;
                            
                            // Show player info
                            surahNameEl.textContent = `سورة ${document.querySelector('.surah-title').textContent.trim()}`;
                            ayahNumberEl.textContent = `آية ${ayah}`;
                            
                            // Show player
                            audioWrapper.classList.remove('d-none');
                            
                            // Play audio
                            audioPlayer.play();
                            playPauseBtn.classList.add('playing');
                        }
                    })
                    .catch(error => console.error('Error fetching audio:', error));
            });
        });
        
        // Play/Pause button
        playPauseBtn.addEventListener('click', function() {
            if (audioPlayer.paused) {
                audioPlayer.play();
                this.classList.add('playing');
            } else {
                audioPlayer.pause();
                this.classList.remove('playing');
            }
        });
        
        // Close player
        closePlayerBtn.addEventListener('click', function() {
            audioPlayer.pause();
            audioWrapper.classList.add('d-none');
            playPauseBtn.classList.remove('playing');
        });
        
        // Update progress bar
        audioPlayer.addEventListener('timeupdate', function() {
            const progress = (audioPlayer.currentTime / audioPlayer.duration) * 100;
            progressBar.style.width = `${progress}%`;
            
            // Update current time
            currentTimeEl.textContent = formatTime(audioPlayer.currentTime);
        });
        
        // Set duration when metadata is loaded
        audioPlayer.addEventListener('loadedmetadata', function() {
            durationEl.textContent = formatTime(audioPlayer.duration);
        });
        
        // Handle end of audio
        audioPlayer.addEventListener('ended', function() {
            playPauseBtn.classList.remove('playing');
            progressBar.style.width = '0%';
        });
        
        // Format time helper function
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }
        
        // Bookmark functionality
        if (document.querySelector('.add-bookmark')) {
            document.querySelector('.add-bookmark').addEventListener('click', function() {
                const surah = this.dataset.surah;
                const ayah = this.dataset.ayah;
                const name = document.querySelector('.surah-title').textContent.trim();
                
                // Add bookmark via API
                fetch(`${baseUrl}/add-bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `surah=${surah}&ayah=${ayah}&name=${name}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تمت إضافة المفضلة بنجاح');
                        // Reload page to show new bookmark
                        window.location.reload();
                    } else {
                        alert(data.error || 'حدث خطأ أثناء إضافة المفضلة');
                    }
                })
                .catch(error => console.error('Error adding bookmark:', error));
            });
        }
        
        // Remove bookmark functionality
        document.querySelectorAll('.remove-bookmark').forEach(btn => {
            btn.addEventListener('click', function() {
                const bookmarkId = this.dataset.bookmarkId;
                
                if (confirm('هل أنت متأكد من حذف هذه المفضلة؟')) {
                    // Remove bookmark via API
                    fetch(`${baseUrl}/api/quran/remove-bookmark`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `bookmark_id=${bookmarkId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the bookmark item from DOM
                            this.closest('.bookmark-item').parentElement.remove();
                        } else {
                            alert(data.error || 'حدث خطأ أثناء حذف المفضلة');
                        }
                    })
                    .catch(error => console.error('Error removing bookmark:', error));
                }
            });
        });
        
        // Scroll to top button
        document.getElementById('scroll-to-top').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Highlight current ayah if specified
        if (window.location.search.includes('ayah=')) {
            const urlParams = new URLSearchParams(window.location.search);
            const ayahNumber = urlParams.get('ayah');
            const ayahElement = document.getElementById(`ayah-${ayahNumber}`);
            
            if (ayahElement) {
                // Scroll to the ayah
                setTimeout(() => {
                    ayahElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    ayahElement.classList.add('highlighted');
                    
                    // Remove highlight after a few seconds
                    setTimeout(() => {
                        ayahElement.classList.remove('highlighted');
                    }, 3000);
                }, 500);
            }
        }
    });
</script>

<style>
    .quran-container {
        padding: 30px 0;
    }
    
    .surah-list-container {
        position: sticky;
        top: 20px;
    }
    
    .surah-list {
        max-height: 600px;
        overflow-y: auto;
        overflow-x: hidden;
    }
    
    .surah-list-item {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease;
    }
    
    .dark-theme .surah-list-item {
        border-bottom: 1px solid #333;
        color: #f5f5f5;
    }
    
    .surah-list-item:hover {
        background-color: #f5f5f5;
    }
    
    .dark-theme .surah-list-item:hover {
        background-color: #333;
    }
    
    .surah-list-item.active {
        background-color: #e8f4ff;
        color: #0d6efd;
    }
    
    .dark-theme .surah-list-item.active {
        background-color: #2c3e50;
        color: #77a7ff;
    }
    
    .surah-number {
        width: 30px;
        height: 30px;
        background-color: #f0f0f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        font-weight: bold;
    }
    
    .dark-theme .surah-number {
        background-color: #444;
    }
    
    .surah-list-item.active .surah-number {
        background-color: #0d6efd;
        color: #fff;
    }
    
    .dark-theme .surah-list-item.active .surah-number {
        background-color: #77a7ff;
        color: #222;
    }
    
    .surah-name {
        flex-grow: 1;
    }
    
    .arabic-name {
        display: block;
        font-size: 1.1rem;
    }
    
    .english-name {
        display: block;
        font-size: 0.8rem;
        color: #777;
    }
    
    .dark-theme .english-name {
        color: #aaa;
    }
    
    .surah-ayah-count {
        font-size: 0.8rem;
        color: #777;
    }
    
    .dark-theme .surah-ayah-count {
        color: #aaa;
    }
    
    .surah-header {
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
    }
    
    .dark-theme .surah-header {
        border-bottom: 1px solid #333;
    }
    
    .surah-title {
        font-size: 2rem;
        margin-bottom: 5px;
    }
    
    .surah-title-en {
        font-size: 1rem;
        color: #777;
        margin-right: 10px;
    }
    
    .dark-theme .surah-title-en {
        color: #aaa;
    }
    
    .surah-info {
        color: #777;
        font-size: 0.9rem;
    }
    
    .dark-theme .surah-info {
        color: #aaa;
    }
    
    .revelation-type {
        margin-right: 15px;
    }
    
    .bismillah {
        font-size: 2rem;
        font-family: 'Scheherazade New', serif;
        color: #0d6efd;
    }
    
    .dark-theme .bismillah {
        color: #77a7ff;
    }
    
    .quran-text {
        font-family: 'Scheherazade New', serif;
        font-size: 2rem;
        line-height: 1.8;
        text-align: right;
        direction: rtl;
    }
    
    .verse {
        position: relative;
        margin-bottom: 20px;
        padding: 10px;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }
    
    .verse:hover {
        background-color: #f5f5f5;
    }
    
    .dark-theme .verse:hover {
        background-color: #333;
    }
    
    .verse.active, .verse.highlighted {
        background-color: #e8f4ff;
    }
    
    .dark-theme .verse.active, .dark-theme .verse.highlighted {
        background-color: #2c3e50;
    }
    
    .verse-text {
        position: relative;
        z-index: 1;
    }
    
    .verse-number {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        background-color: #f0f0f0;
        border-radius: 50%;
        font-size: 0.7rem;
        color: #777;
        margin-right: 5px;
        position: relative;
        top: -5px;
    }
    
    .dark-theme .verse-number {
        background-color: #444;
        color: #aaa;
    }
    
    .verse-actions {
        z-index: 1000;
        position: absolute;
        top: 10px;
        left: 10px;
        display: none;
    }
    
    .verse:hover .verse-actions {
        display: flex;
    }
    
    .verse-audio, .verse-bookmark {
        background: none;
        border: none;
        color: #777;
        font-size: 1.2rem;
        margin-left: 10px;
        cursor: pointer;
        transition: color 0.2s ease;
    }
    
    .dark-theme .verse-audio, .dark-theme .verse-bookmark {
        color: #aaa;
    }
    
    .verse-audio:hover, .verse-bookmark:hover {
        color: #0d6efd;
    }
    
    .dark-theme .verse-audio:hover, .dark-theme .verse-bookmark:hover {
        color: #77a7ff;
    }
    
    .audio-player-wrapper {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 1000;
        transition: transform 0.3s ease;
    }
    
    .dark-theme .audio-player-wrapper {
        background-color: #222;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.3);
    }
    
    .audio-player {
        padding: 15px;
        display: flex;
        flex-direction: column;
    }
    
    .audio-player-info {
        margin-bottom: 10px;
        text-align: center;
    }
    
    .audio-surah-name {
        font-weight: bold;
        margin-left: 10px;
    }
    
    .audio-player-controls {
        display: flex;
        align-items: center;
    }
    
    .audio-player-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #0d6efd;
        cursor: pointer;
    }
    
    .dark-theme .audio-player-btn {
        color: #77a7ff;
    }
    
    .play-pause .fa-pause {
        display: none;
    }
    
    .play-pause.playing .fa-play {
        display: none;
    }
    
    .play-pause.playing .fa-pause {
        display: inline-block;
    }
    
    .audio-progress {
        flex-grow: 1;
        height: 8px;
        background-color: #eee;
        border-radius: 4px;
        margin: 0 15px;
        position: relative;
    }
    
    .dark-theme .audio-progress {
        background-color: #444;
    }
    
    .audio-progress-bar {
        height: 100%;
        background-color: #0d6efd;
        border-radius: 4px;
        width: 0%;
    }
    
    .dark-theme .audio-progress-bar {
        background-color: #77a7ff;
    }
    
    .audio-time {
        font-size: 0.8rem;
        color: #777;
        margin-right: 15px;
    }
    
    .dark-theme .audio-time {
        color: #aaa;
    }
    
    .close-player {
        font-size: 1rem;
    }
    
    .bookmark-item {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
    }
    
    .dark-theme .bookmark-item {
        background-color: #333;
    }
    
    .bookmark-item:hover {
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    
    .dark-theme .bookmark-item:hover {
        box-shadow: 0 3px 8px rgba(0,0,0,0.3);
    }
    
    .bookmark-item a {
        text-decoration: none;
        color: inherit;
        flex-grow: 1;
    }
    
    .bookmark-surah {
        font-weight: bold;
    }
    
    .bookmark-location {
        font-size: 0.8rem;
        color: #777;
    }
    
    .dark-theme .bookmark-location {
        color: #aaa;
    }
</style> 