<?php
// Set page title for layout
$pageTitle = 'سماع القرآن الكريم';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">اختيار السورة</h5>
                </div>
                <div class="card-body">
                    <select id="surah-select" class="form-select mb-3">
                        <?php foreach ($surahs as $surah): ?>
                            <option value="<?= $surah['number'] ?>" <?= $surah['number'] == $surahNumber ? 'selected' : '' ?>>
                                <?= $surah['number'] ?> - <?= $surah['name'] ?> (<?= $surah['englishName'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <h5 class="mt-4">اختيار القارئ</h5>
                    <select id="reciter-select" class="form-select">
                        <?php foreach ($reciters as $id => $name): ?>
                            <option value="<?= $id ?>" <?= $id == $selectedReciter ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <div class="mt-4">
                        <button id="download-surah" class="btn btn-success w-100">
                            <i class="fas fa-download me-2"></i> تحميل السورة كاملة
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">معلومات السورة</h5>
                </div>
                <div class="card-body">
                    <?php if ($audioData): ?>
                        <h5 class="text-center mb-3"><?= $audioData['surah']['name'] ?></h5>
                        <p><strong>الاسم بالإنجليزية:</strong> <?= $audioData['surah']['englishName'] ?></p>
                        <p><strong>الترجمة الإنجليزية:</strong> <?= $audioData['surah']['englishNameTranslation'] ?></p>
                        <p><strong>عدد الآيات:</strong> <?= count($audioData['ayahs']) ?></p>
                    <?php else: ?>
                        <p class="text-center text-muted">لا توجد معلومات متاحة</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">مشغل السورة</h5>
                    <?php if ($audioData): ?>
                        <span class="text-light"><?= $audioData['surah']['name'] ?> - <?= $audioData['surah']['englishName'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <?php if ($audioData && count($audioData['ayahs']) > 0): ?>
                        <div class="mb-4">
                            <div id="audio-player-container" class="mb-4">
                                <audio id="audio-player" controls class="w-100">
                                    <source src="<?= $audioData['ayahs'][0]['audioUrl'] ?>" type="audio/mp3">
                                    متصفحك لا يدعم تشغيل الصوت.
                                </audio>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <button id="prev-ayah" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-step-backward"></i> الآية السابقة
                                </button>
                                <div id="current-ayah-info" class="text-center">
                                    الآية: <span id="current-ayah-number">1</span> / <?= count($audioData['ayahs']) ?>
                                </div>
                                <button id="next-ayah" class="btn btn-sm btn-outline-primary">
                                    الآية التالية <i class="fas fa-step-forward"></i>
                                </button>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="auto-play-next" checked>
                                <label class="form-check-label" for="auto-play-next">تشغيل الآية التالية تلقائياً</label>
                            </div>
                        </div>
                        
                        <div id="ayah-text" class="p-3 bg-light rounded mb-4 text-center arabic-text fs-4">
                            <?= $audioData['ayahs'][0]['text'] ?>
                        </div>
                        
                        <hr>
                        
                        <h5 class="mb-3">قائمة الآيات</h5>
                        <div class="ayah-list">
                            <?php foreach ($audioData['ayahs'] as $index => $ayah): ?>
                                <div class="ayah-item p-2 rounded mb-2 <?= $index === 0 ? 'bg-light' : '' ?>" 
                                     data-index="<?= $index ?>" 
                                     data-audio="<?= $ayah['audioUrl'] ?>"
                                     data-text="<?= htmlspecialchars($ayah['text']) ?>">
                                    <div class="d-flex justify-content-between">
                                        <span class="ayah-number"><?= $ayah['numberInSurah'] ?></span>
                                        <button class="btn btn-sm btn-outline-secondary play-ayah">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning text-center">
                            لا توجد بيانات صوتية متاحة لهذه السورة. يرجى تحديد سورة أخرى أو قارئ آخر.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Set base URL for API calls
const baseUrl = "<?= baseUrl() ?>";

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const surahSelect = document.getElementById('surah-select');
    const reciterSelect = document.getElementById('reciter-select');
    const downloadBtn = document.getElementById('download-surah');
    const audioPlayer = document.getElementById('audio-player');
    const prevAyahBtn = document.getElementById('prev-ayah');
    const nextAyahBtn = document.getElementById('next-ayah');
    const currentAyahSpan = document.getElementById('current-ayah-number');
    const autoPlayCheckbox = document.getElementById('auto-play-next');
    const ayahText = document.getElementById('ayah-text');
    const ayahItems = document.querySelectorAll('.ayah-item');
    
    // Current ayah index
    let currentAyahIndex = 0;
    
    // Change surah or reciter
    surahSelect.addEventListener('change', function() {
        window.location.href = `${baseUrl}/audio?surah=${this.value}&reciter=${reciterSelect.value}`;
    });
    
    reciterSelect.addEventListener('change', function() {
        window.location.href = `${baseUrl}/audio?surah=${surahSelect.value}&reciter=${this.value}`;
    });
    
    // Download surah
    downloadBtn.addEventListener('click', function() {
        fetch(`${baseUrl}/api/audio/download?surah=${surahSelect.value}&reciter=${reciterSelect.value}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create a temporary link to download the file
                    const a = document.createElement('a');
                    a.href = data.downloadUrl;
                    a.download = data.fileName;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } else {
                    alert('حدث خطأ: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('حدث خطأ أثناء تحميل السورة');
            });
    });
    
    // Play next ayah when current one ends
    audioPlayer.addEventListener('ended', function() {
        if (autoPlayCheckbox.checked) {
            playNextAyah();
        }
    });
    
    // Previous ayah button
    prevAyahBtn.addEventListener('click', function() {
        if (currentAyahIndex > 0) {
            currentAyahIndex--;
            updateAudioPlayer();
        }
    });
    
    // Next ayah button
    nextAyahBtn.addEventListener('click', function() {
        playNextAyah();
    });
    
    // Play specific ayah when clicking on it in the list
    ayahItems.forEach(item => {
        item.addEventListener('click', function() {
            currentAyahIndex = parseInt(this.dataset.index);
            updateAudioPlayer();
        });
        
        const playBtn = item.querySelector('.play-ayah');
        playBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            currentAyahIndex = parseInt(item.dataset.index);
            updateAudioPlayer();
        });
    });
    
    // Play next ayah
    function playNextAyah() {
        if (currentAyahIndex < ayahItems.length - 1) {
            currentAyahIndex++;
            updateAudioPlayer();
        }
    }
    
    // Update audio player with current ayah
    function updateAudioPlayer() {
        if (ayahItems.length === 0) return;
        
        const currentAyah = ayahItems[currentAyahIndex];
        const audioUrl = currentAyah.dataset.audio;
        const text = currentAyah.dataset.text;
        
        // Update audio source
        audioPlayer.src = audioUrl;
        audioPlayer.load();
        audioPlayer.play();
        
        // Update ayah text
        ayahText.textContent = text;
        
        // Update current ayah number
        currentAyahSpan.textContent = (currentAyahIndex + 1).toString();
        
        // Update highlighted ayah in list
        ayahItems.forEach(item => item.classList.remove('bg-light'));
        currentAyah.classList.add('bg-light');
        
        // Scroll to current ayah in the list
        currentAyah.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
});
</script>

<script src="<?= asset('js/audio-player.js') ?>"></script>

<style>
.arabic-text {
    font-family: 'Amiri', 'Traditional Arabic', serif;
    line-height: 2;
}

.ayah-item {
    cursor: pointer;
    transition: background-color 0.2s;
    border: 1px solid #eee;
}

.ayah-item:hover {
    background-color: #f8f9fa;
}

.ayah-list {
    max-height: 400px;
    overflow-y: auto;
}

.ayah-number {
    font-weight: bold;
}

/* Dark mode adjustments */
@media (prefers-color-scheme: dark) {
    .bg-light {
        background-color: #2d3133 !important;
    }
    
    .ayah-item {
        border-color: #343a40;
    }
    
    .ayah-item:hover {
        background-color: #343a40;
    }
}
</style> 