<?php
require_once BASE_PATH . '/app/controllers/Controller.php';
require_once BASE_PATH . '/app/models/User.php';

/**
 * Quran Controller
 * Handles the Quran reader functionality
 */
class QuranController extends Controller {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display the Quran reader page
     * 
     * @return void
     */
    public function index() {
        // Get the list of Surahs
        $surahs = $this->getSurahs();
        
        // Default to first Surah if none selected
        $surahNumber = $this->get('surah', 1);
        $ayahNumber = $this->get('ayah', 1);
        
        // Get the selected Surah
        $surah = $this->getSurah($surahNumber);
        
        // If failed to get surah, try one more time without cache
        if ($surah === null) {
            error_log("Retrying to fetch Surah {$surahNumber} without cache");
            // Clear cache for this request
            $url = QURAN_API_URL . '/surah/' . $surahNumber;
            $cacheKey = $url . serialize([]);
            $cacheFile = BASE_PATH . '/cache/' . md5($cacheKey) . '.json';
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
            
            // Try again
            $surah = $this->getSurah($surahNumber);
            
            // If still failed, use fallback data for common surahs
            if ($surah === null) {
                $surah = $this->getFallbackSurah($surahNumber);
            }
        }
        
        // Get available reciters
        $reciters = $this->getReciters();
        
        // Get bookmarks if user is logged in
        $bookmarks = [];
        if (isLoggedIn()) {
            $bookmarks = $this->userModel->getBookmarks(getCurrentUserId());
            
            // Only track history if we have valid surah data
            if ($surah !== null) {
                // Track reading history
                $this->userModel->addReadingHistory(
                    getCurrentUserId(),
                    $surahNumber,
                    $ayahNumber
                );
                
                // Update last accessed position in user preferences
                $this->userModel->updatePreferences(
                    getCurrentUserId(),
                    ['last_surah' => $surahNumber, 'last_ayah' => $ayahNumber]
                );
            }
        }
        
        // Render the Quran reader view
        $this->render('quran/index', [
            'surahs' => $surahs,
            'surah' => $surah,
            'reciters' => $reciters,
            'bookmarks' => $bookmarks,
            'currentAyah' => $ayahNumber,
            'apiError' => ($surah === null) // Pass flag if API error occurred
        ]);
    }
    
    /**
     * Get a specific Surah with its verses
     * 
     * @param int $surahNumber The Surah number
     * @return array The Surah data
     */
    public function getSurah($surahNumber) {
        $url = QURAN_API_URL . '/surah/' . $surahNumber;
        $response = $this->apiRequest($url, 'GET', [], true);
        
        if (!isset($response['data'])) {
            error_log("Failed to get Surah data for number: {$surahNumber}");
            return null;
        }
        
        // Get the verses (ayahs) for this Surah
        $ayahsUrl = QURAN_API_URL . '/surah/' . $surahNumber . '/ar.alafasy';
        $ayahsResponse = $this->apiRequest($ayahsUrl, 'GET', [], true);
        
        if (isset($ayahsResponse['data']['ayahs'])) {
            $response['data']['ayahs'] = $ayahsResponse['data']['ayahs'];
        } else {
            error_log("Failed to get ayahs for Surah: {$surahNumber}");
        }
        
        return $response['data'];
    }
    
    /**
     * Get the list of all Surahs
     * 
     * @return array List of Surahs
     */
    public function getSurahs() {
        $url = QURAN_API_URL . '/surah';
        $response = $this->apiRequest($url, 'GET', [], true);
        
        if (isset($response['data'])) {
            return $response['data'];
        }
        
        error_log("Failed to get list of Surahs, using fallback list");
        // Return a fallback list of the first 10 surahs if API fails
        return $this->getFallbackSurahsList();
    }
    
    /**
     * Get a fallback list of surahs when API fails
     * 
     * @return array Fallback list of surahs (first 10)
     */
    private function getFallbackSurahsList() {
        return [
            [
                'number' => 1,
                'name' => 'سُورَةُ ٱلْفَاتِحَةِ',
                'englishName' => 'Al-Faatiha',
                'englishNameTranslation' => 'The Opening',
                'numberOfAyahs' => 7,
                'revelationType' => 'Meccan'
            ],
            [
                'number' => 2,
                'name' => 'سُورَةُ البَقَرَةِ',
                'englishName' => 'Al-Baqara',
                'englishNameTranslation' => 'The Cow',
                'numberOfAyahs' => 286,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 3,
                'name' => 'سُورَةُ آلِ عِمۡرَانَ',
                'englishName' => 'Aal-Imran',
                'englishNameTranslation' => 'The Family of Imraan',
                'numberOfAyahs' => 200,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 4,
                'name' => 'سُورَةُ النِّسَاءِ',
                'englishName' => 'An-Nisaa',
                'englishNameTranslation' => 'The Women',
                'numberOfAyahs' => 176,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 5,
                'name' => 'سُورَةُ المَائـِدَةِ',
                'englishName' => 'Al-Maaida',
                'englishNameTranslation' => 'The Table',
                'numberOfAyahs' => 120,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 6,
                'name' => 'سُورَةُ الأَنۡعَامِ',
                'englishName' => 'Al-An\'aam',
                'englishNameTranslation' => 'The Cattle',
                'numberOfAyahs' => 165,
                'revelationType' => 'Meccan'
            ],
            [
                'number' => 7,
                'name' => 'سُورَةُ الأَعۡرَافِ',
                'englishName' => 'Al-A\'raaf',
                'englishNameTranslation' => 'The Heights',
                'numberOfAyahs' => 206,
                'revelationType' => 'Meccan'
            ],
            [
                'number' => 8,
                'name' => 'سُورَةُ الأَنفَالِ',
                'englishName' => 'Al-Anfaal',
                'englishNameTranslation' => 'The Spoils of War',
                'numberOfAyahs' => 75,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 9,
                'name' => 'سُورَةُ التَّوۡبَةِ',
                'englishName' => 'At-Tawba',
                'englishNameTranslation' => 'The Repentance',
                'numberOfAyahs' => 129,
                'revelationType' => 'Medinan'
            ],
            [
                'number' => 10,
                'name' => 'سُورَةُ يُونُسَ',
                'englishName' => 'Yunus',
                'englishNameTranslation' => 'Jonah',
                'numberOfAyahs' => 109,
                'revelationType' => 'Meccan'
            ]
        ];
    }
    
    /**
     * Get available Quran reciters
     * 
     * @return array List of reciters
     */
    public function getReciters() {
        return [
            'ar.alafasy' => 'Mishary Rashid Alafasy',
            'ar.abdulbasitmurattal' => 'Abdul Basit Abdul Samad',
            'ar.abdurrahmaansudais' => 'Abdur-Rahman As-Sudais',
            'ar.minshawi' => 'Mohamed Siddiq Al-Minshawi',
            'ar.muhammadayyoub' => 'Muhammad Ayyoub',
        ];
    }
    
    /**
     * API endpoint for getting a specific ayah audio
     * 
     * @return void Outputs JSON response
     */
    public function getAyahAudio() {
        $surah = $this->get('surah');
        $ayah = $this->get('ayah');
        $reciter = $this->get('reciter', 'ar.alafasy');
        
        if (!$surah || !$ayah) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing surah or ayah parameter']);
            return;
        }
        
        $url = QURAN_API_URL . '/ayah/' . $surah . ':' . $ayah . '/' . $reciter;
        $response = $this->apiRequest($url);
        
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
    /**
     * Add a bookmark for the logged-in user
     * 
     * @return void Outputs JSON response
     */
    public function addBookmark() {
        if (!isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User must be logged in to add bookmarks']);
            return;
        }
        
        $surah = $this->post('surah');
        $ayah = $this->post('ayah');
        $name = $this->post('name', '');
        
        if (!$surah || !$ayah) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing surah or ayah parameter']);
            return;
        }
        
        $success = $this->userModel->addBookmark(
            getCurrentUserId(),
            $surah,
            $ayah,
            $name
        );
        
        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Bookmark added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add bookmark']);
        }
    }
    
    /**
     * Remove a bookmark for the logged-in user
     * 
     * @return void Outputs JSON response
     */
    public function removeBookmark() {
        if (!isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User must be logged in to remove bookmarks']);
            return;
        }
        
        $bookmarkId = $this->post('bookmark_id');
        
        if (!$bookmarkId) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing bookmark_id parameter']);
            return;
        }
        
        $success = $this->userModel->deleteBookmark(
            $bookmarkId,
            getCurrentUserId()
        );
        
        header('Content-Type: application/json');
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Bookmark removed successfully']);
        } else {
            echo json_encode(['error' => 'Failed to remove bookmark']);
        }
    }
    
    /**
     * Get fallback data for common surahs when API fails
     * 
     * @param int $surahNumber The Surah number
     * @return array|null Fallback Surah data or null if not available
     */
    private function getFallbackSurah($surahNumber) {
        // Only provide fallback for the first surah (Al-Fatiha) for now
        if ($surahNumber == 1) {
            return [
                'number' => 1,
                'name' => 'سُورَةُ ٱلْفَاتِحَةِ',
                'englishName' => 'Al-Faatiha',
                'englishNameTranslation' => 'The Opening',
                'numberOfAyahs' => 7,
                'revelationType' => 'Meccan',
                'ayahs' => [
                    [
                        'number' => 1,
                        'text' => 'بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ',
                        'numberInSurah' => 1,
                    ],
                    [
                        'number' => 2,
                        'text' => 'الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ',
                        'numberInSurah' => 2,
                    ],
                    [
                        'number' => 3,
                        'text' => 'الرَّحْمَٰنِ الرَّحِيمِ',
                        'numberInSurah' => 3,
                    ],
                    [
                        'number' => 4,
                        'text' => 'مَالِكِ يَوْمِ الدِّينِ',
                        'numberInSurah' => 4,
                    ],
                    [
                        'number' => 5,
                        'text' => 'إِيَّاكَ نَعْبُدُ وَإِيَّاكَ نَسْتَعِينُ',
                        'numberInSurah' => 5,
                    ],
                    [
                        'number' => 6,
                        'text' => 'اهْدِنَا الصِّرَاطَ الْمُسْتَقِيمَ',
                        'numberInSurah' => 6,
                    ],
                    [
                        'number' => 7,
                        'text' => 'صِرَاطَ الَّذِينَ أَنْعَمْتَ عَلَيْهِمْ غَيْرِ الْمَغْضُوبِ عَلَيْهِمْ وَلَا الضَّالِّينَ',
                        'numberInSurah' => 7,
                    ],
                ]
            ];
        }
        
        return null;
    }
}
?> 