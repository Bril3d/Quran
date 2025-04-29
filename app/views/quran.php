<?php
// Set page title
$pageTitle = 'القرآن الكريم';
?>

<div class="container-fluid quran-container">
    <div class="row">
        <!-- Surah List Sidebar -->
        <div class="col-md-3 surah-sidebar">
            <div class="card">
                <div class="card-header">
                    <h5>Surah List</h5>
                </div>
                <div class="card-body">
                    <select id="surah-select" class="form-select mb-3">
                        <?php if(isset($data['surahs'])): ?>
                            <?php foreach($data['surahs'] as $surah): ?>
                                <option value="<?= $surah->id ?>" 
                                    data-name="<?= $surah->name ?>"
                                    data-name-en="<?= $surah->nameEn ?>"
                                    data-ayahs="<?= $surah->ayahCount ?>"
                                    <?= ($data['currentSurah'] == $surah->id) ? 'selected' : '' ?>>
                                    <?= $surah->id ?>. <?= $surah->nameEn ?> (<?= $surah->name ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    
                    <!-- Reciter Selection -->
                    <div class="form-group mb-3">
                        <label for="reciter-select">Reciter:</label>
                        <select id="reciter-select" class="form-select">
                            <?php if(isset($data['reciters'])): ?>
                                <?php foreach($data['reciters'] as $reciter): ?>
                                    <option value="<?= $reciter->id ?>" 
                                        <?= ($data['currentReciter'] == $reciter->id) ? 'selected' : '' ?>>
                                        <?= $reciter->name ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="default">Default Reciter</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <!-- Autoplay Toggle -->
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="autoplay-switch">
                        <label class="form-check-label" for="autoplay-switch">Autoplay</label>
                    </div>
                    
                    <!-- Download Button -->
                    <button id="download-btn" class="btn btn-outline-primary mb-3 w-100">
                        <i class="fas fa-download"></i> Download Surah
                    </button>
                    
                    <!-- Ayah List -->
                    <div id="ayah-list" class="ayah-list-container overflow-auto">
                        <!-- Ayahs will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-md-9">
            <!-- Surah Info -->
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 id="surah-name" class="arabic-text mb-0">
                                <?= isset($data['currentSurahData']) ? $data['currentSurahData']->name : '' ?>
                            </h2>
                            <h4 id="surah-name-en" class="mb-0">
                                <?= isset($data['currentSurahData']) ? $data['currentSurahData']->nameEn : '' ?>
                            </h4>
                            <p class="text-muted">
                                <span id="surah-ayahs"><?= isset($data['currentSurahData']) ? $data['currentSurahData']->ayahCount : '' ?></span> Verses
                            </p>
                        </div>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <!-- Bookmark Button -->
                            <button id="bookmark-btn" class="btn btn-outline-warning" 
                                data-surah="<?= $data['currentSurah'] ?>" 
                                data-ayah="<?= $data['currentAyah'] ?>">
                                <i class="fas <?= isset($data['isBookmarked']) && $data['isBookmarked'] ? 'fa-bookmark' : 'fa-bookmark-o' ?>"></i>
                                Bookmark
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Audio Player -->
            <div class="card mb-3">
                <div class="card-body audio-player-container">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 id="current-ayah" class="mb-0"><?= $data['currentSurah'] ?>:<?= $data['currentAyah'] ?></h5>
                        <div>
                            <button id="prev-ayah-btn" class="btn btn-sm btn-outline-secondary me-2">
                                <i class="fas fa-step-backward"></i>
                            </button>
                            <button id="next-ayah-btn" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-step-forward"></i>
                            </button>
                        </div>
                    </div>
                    
                    <audio id="audio-player" controls class="w-100">
                        <source src="" type="audio/mp3">
                        Your browser does not support the audio element.
                    </audio>
                </div>
            </div>
            
            <!-- Ayah Display -->
            <div class="card">
                <div class="card-body">
                    <div id="current-ayah-text" class="arabic-text text-center large-arabic-text">
                        <?= isset($data['currentAyahText']) ? $data['currentAyahText'] : '' ?>
                    </div>
                    
                    <!-- Ayah Translation (if available) -->
                    <?php if(isset($data['currentAyahTranslation'])): ?>
                        <div class="translation-text mt-3">
                            <?= $data['currentAyahTranslation'] ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/audio-player.js') ?>"></script>

<?php if(isset($_SESSION['user_id'])): ?>
<script>
    // Bookmark functionality
    document.getElementById('bookmark-btn').addEventListener('click', function() {
        const surah = this.getAttribute('data-surah');
        const ayah = this.getAttribute('data-ayah');
        const bookmarkIcon = this.querySelector('i');
        
        // Check if already bookmarked
        if (bookmarkIcon.classList.contains('fa-bookmark')) {
            // Remove bookmark
            fetch('<?= baseUrl() ?>/quran/removeBookmark', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `surah=${surah}&ayah=${ayah}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bookmarkIcon.classList.remove('fa-bookmark');
                    bookmarkIcon.classList.add('fa-bookmark-o');
                } else {
                    alert('Error removing bookmark');
                }
            });
        } else {
            // Add bookmark
            fetch('<?= baseUrl() ?>/quran/addBookmark', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `surah=${surah}&ayah=${ayah}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bookmarkIcon.classList.remove('fa-bookmark-o');
                    bookmarkIcon.classList.add('fa-bookmark');
                } else {
                    alert('Error adding bookmark');
                }
            });
        }
    });
</script>
<?php endif; ?> 