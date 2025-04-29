/**
 * Quran Website - Main JavaScript File
 */

// Wait for DOM content to be loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Get user's location for prayer times if geolocation is available
    if (navigator.geolocation && !getCookie('latitude') && !getCookie('longitude')) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Set cookies for location
            setCookie('latitude', position.coords.latitude, 30);
            setCookie('longitude', position.coords.longitude, 30);
            
            // Refresh the page to load new prayer times if on the home or prayer times page
            if (window.location.pathname === '/' || 
                window.location.pathname === '/index.php' || 
                window.location.pathname.includes('prayer-times')) {
                window.location.reload();
            }
        }, function(error) {
            console.log('Error getting location:', error.message);
        });
    }
    
    // Audio player functionality
    initializeAudioPlayer();
    
    // Chat functionality
    initializeChat();
    
    // Quran reader functionality
    initializeQuranReader();
});

/**
 * Initialize the audio player
 */
function initializeAudioPlayer() {
    const audioPlayerEl = document.querySelector('.audio-player');
    if (!audioPlayerEl) return;
    
    const audioEl = document.querySelector('audio');
    const playPauseBtn = document.querySelector('.play-pause');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    const progressBar = document.querySelector('.audio-progress-bar');
    const currentTimeEl = document.querySelector('.current-time');
    const durationEl = document.querySelector('.duration');
    
    // Play/Pause functionality
    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', function() {
            if (audioEl.paused) {
                audioEl.play();
                playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
            } else {
                audioEl.pause();
                playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        });
    }
    
    // Previous track
    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            const currentAyah = parseInt(audioEl.dataset.ayah);
            if (currentAyah > 1) {
                loadAyahAudio(currentAyah - 1);
            }
        });
    }
    
    // Next track
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            const currentAyah = parseInt(audioEl.dataset.ayah);
            const totalAyahs = parseInt(audioEl.dataset.totalAyahs);
            if (currentAyah < totalAyahs) {
                loadAyahAudio(currentAyah + 1);
            }
        });
    }
    
    // Update progress bar
    if (audioEl) {
        audioEl.addEventListener('timeupdate', function() {
            const percentage = (audioEl.currentTime / audioEl.duration) * 100;
            progressBar.style.width = percentage + '%';
            
            // Update current time
            currentTimeEl.textContent = formatTime(audioEl.currentTime);
        });
        
        audioEl.addEventListener('loadedmetadata', function() {
            durationEl.textContent = formatTime(audioEl.duration);
        });
        
        // Click on progress bar to seek
        const audioProgress = document.querySelector('.audio-progress');
        if (audioProgress) {
            audioProgress.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const width = rect.width;
                const x = e.clientX - rect.left;
                
                const percentage = x / width;
                audioEl.currentTime = audioEl.duration * percentage;
            });
        }
    }
    
    // Individual ayah audio buttons
    const ayahAudioBtns = document.querySelectorAll('.verse-audio');
    ayahAudioBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            const ayahNumber = this.dataset.ayah;
            loadAyahAudio(ayahNumber);
        });
    });
}

/**
 * Load audio for a specific ayah
 * 
 * @param {number} ayahNumber The ayah number to load
 */
function loadAyahAudio(ayahNumber) {
    const audioEl = document.querySelector('audio');
    if (!audioEl) return;
    
    // Get surah and reciter with fallbacks
    const surahNumber = audioEl.dataset.surah || document.querySelector('.surah-title') ? 
        document.querySelector('.surah-title').dataset.surah : 1;
    const reciter = audioEl.dataset.reciter || 'ar.alafasy';
    
    if (!surahNumber || !ayahNumber) {
        console.error('Missing surah or ayah number for audio playback');
        return;
    }
    
    // Make API request to get the ayah audio
    fetch(`${window.location.origin}/api/quran/ayah-audio?surah=${surahNumber}&ayah=${ayahNumber}&reciter=${reciter}`)
        .then(response => response.json())
        .then(data => {
            if (data.data && data.data.audio) {
                audioEl.src = data.data.audio;
                audioEl.dataset.ayah = ayahNumber;
                
                // Update active ayah in the list
                document.querySelectorAll('.verse').forEach(verse => {
                    verse.classList.remove('active-verse');
                });
                
                const activeVerse = document.querySelector(`.verse[data-ayah="${ayahNumber}"]`);
                if (activeVerse) {
                    activeVerse.classList.add('active-verse');
                    activeVerse.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Auto play
                audioEl.play();
                const playPauseBtn = document.querySelector('.play-pause');
                if (playPauseBtn) {
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                }
            }
        })
        .catch(error => {
            console.log('Error loading ayah audio:', error);
        });
}

/**
 * Format time from seconds to MM:SS
 * 
 * @param {number} seconds Time in seconds
 * @return {string} Formatted time
 */
function formatTime(seconds) {
    seconds = Math.floor(seconds);
    const minutes = Math.floor(seconds / 60);
    seconds = seconds % 60;
    
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

/**
 * Initialize the chat interface
 */
function initializeChat() {
    const chatForm = document.getElementById('chat-form');
    if (!chatForm) return;
    
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (message) {
            // Add user message to chat
            addMessageToChat(message, 'user');
            messageInput.value = '';
            
            // Show typing indicator
            showTypingIndicator();
            
            // Make API request for recommendation
            fetch(`${window.location.origin}/api/chat/recommendation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `message=${encodeURIComponent(message)}`
            })
            .then(response => response.json())
            .then(data => {
                // Hide typing indicator
                hideTypingIndicator();
                
                if (data.recommendation) {
                    // Add bot message to chat
                    addMessageToChat(data.recommendation.message, 'bot');
                    
                    // Show recommendation card
                    showRecommendation(data.recommendation);
                }
            })
            .catch(error => {
                console.log('Error getting recommendation:', error);
                hideTypingIndicator();
                addMessageToChat('عذراً، حدث خطأ أثناء معالجة طلبك. الرجاء المحاولة مرة أخرى.', 'bot');
            });
        }
    });
}

/**
 * Add a message to the chat
 * 
 * @param {string} message The message to add
 * @param {string} sender 'user' or 'bot'
 */
function addMessageToChat(message, sender) {
    const chatMessages = document.querySelector('.chat-messages');
    if (!chatMessages) return;
    
    const messageEl = document.createElement('div');
    messageEl.className = `chat-message ${sender}`;
    
    const contentEl = document.createElement('div');
    contentEl.className = 'message-content';
    contentEl.textContent = message;
    
    messageEl.appendChild(contentEl);
    chatMessages.appendChild(messageEl);
    
    // Scroll to bottom
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

/**
 * Show typing indicator in chat
 */
function showTypingIndicator() {
    const chatMessages = document.querySelector('.chat-messages');
    if (!chatMessages) return;
    
    const typingEl = document.createElement('div');
    typingEl.className = 'chat-message bot typing-indicator';
    typingEl.innerHTML = '<div class="message-content"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>';
    
    chatMessages.appendChild(typingEl);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

/**
 * Hide typing indicator in chat
 */
function hideTypingIndicator() {
    const typingIndicator = document.querySelector('.typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

/**
 * Show recommendation card
 * 
 * @param {object} recommendation The recommendation data
 */
function showRecommendation(recommendation) {
    const recommendationContainer = document.querySelector('.recommendation-container');
    if (!recommendationContainer) return;
    
    // Clear previous recommendations
    recommendationContainer.innerHTML = '';
    
    // Create recommendation card
    const card = document.createElement('div');
    card.className = 'chat-recommendation';
    
    // Recommendation header
    const header = document.createElement('div');
    header.className = 'recommendation-header';
    
    const icon = document.createElement('div');
    icon.className = 'recommendation-icon';
    icon.innerHTML = '<i class="fas fa-book-open"></i>';
    
    const title = document.createElement('h3');
    title.className = 'recommendation-title';
    title.textContent = 'توصية قرآنية لك';
    
    header.appendChild(icon);
    header.appendChild(title);
    
    // Recommendation surah
    const surah = document.createElement('div');
    surah.className = 'recommendation-surah';
    
    const surahName = document.createElement('div');
    surahName.className = 'recommendation-surah-name';
    surahName.textContent = recommendation.name;
    
    const surahEnglishName = document.createElement('div');
    surahEnglishName.className = 'recommendation-surah-english-name';
    surahEnglishName.textContent = `${recommendation.englishName} (${recommendation.englishNameTranslation})`;
    
    surah.appendChild(surahName);
    surah.appendChild(surahEnglishName);
    
    // Recommendation actions
    const actions = document.createElement('div');
    actions.className = 'recommendation-actions';
    actions.innerHTML = `
        <a href="${window.location.origin}/quran?surah=${recommendation.number}" class="btn btn-primary">قراءة السورة</a>
        <a href="${window.location.origin}/audio?surah=${recommendation.number}" class="btn btn-outline-primary ms-2">استماع</a>
    `;
    
    // Add all elements to card
    card.appendChild(header);
    card.appendChild(surah);
    card.appendChild(actions);
    
    // Add card to container
    recommendationContainer.appendChild(card);
}

/**
 * Initialize the Quran reader
 */
function initializeQuranReader() {
    const surahSelect = document.getElementById('surah-select');
    if (!surahSelect) return;
    
    surahSelect.addEventListener('change', function() {
        // Determine current page
        const isAudioPage = window.location.pathname.includes('/audio');
        const path = isAudioPage ? '/audio' : '/quran';
        
        // Preserve existing query parameters
        const currentUrl = new URL(window.location.href);
        currentUrl.pathname = path;
        currentUrl.searchParams.set('surah', this.value);
        
        window.location.href = currentUrl.toString();
    });
    
    const reciterSelect = document.getElementById('reciter-select');
    if (reciterSelect) {
        reciterSelect.addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('reciter', this.value);
            window.location.href = currentUrl.toString();
        });
    }
    
    // Font size controls
    const increaseFontBtn = document.getElementById('increase-font');
    const decreaseFontBtn = document.getElementById('decrease-font');
    const quranText = document.querySelector('.quran-text');
    
    if (increaseFontBtn && decreaseFontBtn && quranText) {
        // Get saved font size from local storage or use default
        let fontSize = localStorage.getItem('quran-font-size') || 1.8;
        quranText.style.fontSize = `${fontSize}rem`;
        
        increaseFontBtn.addEventListener('click', function() {
            if (fontSize < 3) {
                fontSize = parseFloat(fontSize) + 0.1;
                quranText.style.fontSize = `${fontSize}rem`;
                localStorage.setItem('quran-font-size', fontSize);
            }
        });
        
        decreaseFontBtn.addEventListener('click', function() {
            if (fontSize > 1) {
                fontSize = parseFloat(fontSize) - 0.1;
                quranText.style.fontSize = `${fontSize}rem`;
                localStorage.setItem('quran-font-size', fontSize);
            }
        });
    }
}

/**
 * Get cookie value by name
 * 
 * @param {string} name Cookie name
 * @return {string|null} Cookie value or null
 */
function getCookie(name) {
    const cookieArr = document.cookie.split(';');
    
    for (let i = 0; i < cookieArr.length; i++) {
        const cookiePair = cookieArr[i].split('=');
        const cookieName = cookiePair[0].trim();
        
        if (cookieName === name) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    
    return null;
}

/**
 * Set cookie
 * 
 * @param {string} name Cookie name
 * @param {string} value Cookie value
 * @param {number} days Cookie expiration in days
 */
function setCookie(name, value, days) {
    const date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    const expires = 'expires=' + date.toUTCString();
    document.cookie = `${name}=${value};${expires};path=/`;
} 