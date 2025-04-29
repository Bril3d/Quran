document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const audioPlayer = document.getElementById('audio-player');
    const currentAyahEl = document.getElementById('current-ayah');
    const currentAyahTextEl = document.getElementById('current-ayah-text');
    const surahSelect = document.getElementById('surah-select');
    const reciterSelect = document.getElementById('reciter-select');
    const downloadBtn = document.getElementById('download-btn');
    const prevAyahBtn = document.getElementById('prev-ayah-btn');
    const nextAyahBtn = document.getElementById('next-ayah-btn');
    const autoplaySwitch = document.getElementById('autoplay-switch');
    const playlistContainer = document.getElementById('ayah-list');
    
    // State
    let currentSurah = 1;
    let currentAyah = 1;
    let totalAyahs = 7; // Default for Al-Fatiha
    let currentReciter = '';
    let autoplay = false;
    
    // Initialize
    init();
    
    function init() {
        // Set initial values from URL or defaults
        const params = new URLSearchParams(window.location.search);
        currentSurah = parseInt(params.get('surah')) || 1;
        currentAyah = parseInt(params.get('ayah')) || 1;
        
        // Set select values
        if (surahSelect) {
            surahSelect.value = currentSurah;
            handleSurahChange();
        }
        
        if (reciterSelect) {
            currentReciter = reciterSelect.value;
        }
        
        // Load initial audio
        loadAyah(currentSurah, currentAyah);
        
        // Update UI
        updateAyahList();
        highlightCurrentAyah();
    }
    
    // Event Listeners
    if (surahSelect) {
        surahSelect.addEventListener('change', handleSurahChange);
    }
    
    if (reciterSelect) {
        reciterSelect.addEventListener('change', function() {
            currentReciter = this.value;
            loadAyah(currentSurah, currentAyah);
        });
    }
    
    if (downloadBtn) {
        downloadBtn.addEventListener('click', downloadSurah);
    }
    
    if (prevAyahBtn) {
        prevAyahBtn.addEventListener('click', playPreviousAyah);
    }
    
    if (nextAyahBtn) {
        nextAyahBtn.addEventListener('click', playNextAyah);
    }
    
    if (autoplaySwitch) {
        autoplaySwitch.addEventListener('change', function() {
            autoplay = this.checked;
        });
    }
    
    if (audioPlayer) {
        audioPlayer.addEventListener('ended', function() {
            if (autoplay) {
                playNextAyah();
            }
        });
    }
    
    // Functions
    function handleSurahChange() {
        currentSurah = parseInt(surahSelect.value);
        // Get total ayahs for this surah from data attribute or API
        const selectedOption = surahSelect.options[surahSelect.selectedIndex];
        totalAyahs = parseInt(selectedOption.getAttribute('data-ayahs')) || 1;
        
        // Reset to first ayah when changing surah
        currentAyah = 1;
        
        // Update URL without reloading
        const url = new URL(window.location);
        url.searchParams.set('surah', currentSurah);
        url.searchParams.set('ayah', currentAyah);
        window.history.pushState({}, '', url);
        
        // Load the first ayah of the new surah
        loadAyah(currentSurah, currentAyah);
        
        // Update UI
        updateAyahList();
        highlightCurrentAyah();
        
        // Update surah info sections
        updateSurahInfo();
    }
    
    function updateSurahInfo() {
        const selectedOption = surahSelect.options[surahSelect.selectedIndex];
        const surahName = selectedOption.getAttribute('data-name') || '';
        const surahNameEn = selectedOption.getAttribute('data-name-en') || '';
        const surahAyahs = selectedOption.getAttribute('data-ayahs') || '';
        
        // Update DOM elements if they exist
        const surahNameEl = document.getElementById('surah-name');
        const surahNameEnEl = document.getElementById('surah-name-en');
        const surahAyahsEl = document.getElementById('surah-ayahs');
        
        if (surahNameEl) surahNameEl.textContent = surahName;
        if (surahNameEnEl) surahNameEnEl.textContent = surahNameEn;
        if (surahAyahsEl) surahAyahsEl.textContent = surahAyahs;
    }
    
    function loadAyah(surah, ayah) {
        if (ayah < 1 || ayah > totalAyahs) return;
        
        if (audioPlayer) {
            // Construct audio URL based on reciter, surah, and ayah
            const audioUrl = `/public/assets/audio/${currentReciter}/${String(surah).padStart(3, '0')}${String(ayah).padStart(3, '0')}.mp3`;
            audioPlayer.src = audioUrl;
            audioPlayer.load();
            audioPlayer.play().catch(e => console.log('Auto-play prevented:', e));
        }
        
        // Update current ayah display
        if (currentAyahEl) {
            currentAyahEl.textContent = `${surah}:${ayah}`;
        }
        
        // Fetch and update the ayah text if needed
        fetchAyahText(surah, ayah);
        
        // Update URL without reloading
        const url = new URL(window.location);
        url.searchParams.set('surah', surah);
        url.searchParams.set('ayah', ayah);
        window.history.pushState({}, '', url);
        
        // Update current state
        currentSurah = surah;
        currentAyah = ayah;
        
        // Highlight current ayah in list
        highlightCurrentAyah();
    }
    
    function fetchAyahText(surah, ayah) {
        // This would typically be an AJAX call to get the ayah text
        // For demonstration, we'll just update from existing DOM if available
        const ayahItem = document.querySelector(`[data-surah="${surah}"][data-ayah="${ayah}"]`);
        
        if (ayahItem && currentAyahTextEl) {
            const textContent = ayahItem.querySelector('.ayah-text')?.textContent || '';
            currentAyahTextEl.textContent = textContent;
        }
    }
    
    function playPreviousAyah() {
        let prevAyah = currentAyah - 1;
        let prevSurah = currentSurah;
        
        if (prevAyah < 1) {
            // Go to previous surah, last ayah
            prevSurah--;
            if (prevSurah < 1) {
                // Already at first surah, first ayah
                return;
            }
            // Need to get total ayahs for previous surah
            const prevSurahOption = surahSelect.querySelector(`option[value="${prevSurah}"]`);
            if (prevSurahOption) {
                prevAyah = parseInt(prevSurahOption.getAttribute('data-ayahs')) || 1;
                
                // Update surah select
                surahSelect.value = prevSurah;
                totalAyahs = prevAyah;
                updateSurahInfo();
                updateAyahList();
            } else {
                return;
            }
        }
        
        loadAyah(prevSurah, prevAyah);
    }
    
    function playNextAyah() {
        let nextAyah = currentAyah + 1;
        let nextSurah = currentSurah;
        
        if (nextAyah > totalAyahs) {
            // Go to next surah, first ayah
            nextSurah++;
            nextAyah = 1;
            
            // Check if next surah exists
            const nextSurahOption = surahSelect.querySelector(`option[value="${nextSurah}"]`);
            if (nextSurahOption) {
                // Update surah select
                surahSelect.value = nextSurah;
                totalAyahs = parseInt(nextSurahOption.getAttribute('data-ayahs')) || 1;
                updateSurahInfo();
                updateAyahList();
            } else {
                // No more surahs
                return;
            }
        }
        
        loadAyah(nextSurah, nextAyah);
    }
    
    function updateAyahList() {
        if (!playlistContainer) return;
        
        // Clear existing list
        playlistContainer.innerHTML = '';
        
        // Create ayah list items
        for (let i = 1; i <= totalAyahs; i++) {
            const ayahItem = document.createElement('div');
            ayahItem.className = 'ayah-item p-2 d-flex justify-content-between align-items-center';
            ayahItem.setAttribute('data-surah', currentSurah);
            ayahItem.setAttribute('data-ayah', i);
            
            // Add ayah number and play button
            ayahItem.innerHTML = `
                <div class="d-flex align-items-center">
                    <span class="ayah-number me-2">${i}</span>
                    <span class="ayah-text">Verse ${i}</span>
                </div>
                <button class="btn btn-sm btn-outline-primary play-ayah-btn">
                    <i class="fas fa-play"></i>
                </button>
            `;
            
            // Add click event
            ayahItem.querySelector('.play-ayah-btn').addEventListener('click', function() {
                loadAyah(currentSurah, i);
            });
            
            playlistContainer.appendChild(ayahItem);
        }
    }
    
    function highlightCurrentAyah() {
        if (!playlistContainer) return;
        
        // Remove highlight from all items
        const items = playlistContainer.querySelectorAll('.ayah-item');
        items.forEach(item => item.classList.remove('active'));
        
        // Add highlight to current ayah
        const currentItem = playlistContainer.querySelector(`[data-surah="${currentSurah}"][data-ayah="${currentAyah}"]`);
        if (currentItem) {
            currentItem.classList.add('active');
            // Scroll to current item
            currentItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
    
    function downloadSurah() {
        // This would typically trigger a download of the entire surah
        alert('Downloading Surah ' + currentSurah + ' by ' + currentReciter);
        // In a real implementation, you would redirect to a download URL or use fetch to get the file
    }
}); 