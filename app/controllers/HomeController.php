<?php
require_once BASE_PATH . '/app/controllers/Controller.php';

/**
 * Home Controller
 * Handles the home page
 */
class HomeController extends Controller {
    /**
     * Display the home page
     * 
     * @return void
     */
    public function index() {
        // Get prayer times for user's location (default to Mecca if not available)
        $latitude = isset($_COOKIE['latitude']) ? $_COOKIE['latitude'] : 21.3891;
        $longitude = isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : 39.8579;
        
        $prayerTimes = $this->getPrayerTimes($latitude, $longitude);
        
        // Get a random Surah for inspiration
        $randomSurah = $this->getRandomSurah();
        
        // Render the home page view
        $this->render('home/index', [
            'prayerTimes' => $prayerTimes,
            'randomSurah' => $randomSurah,
        ]);
    }
    
    /**
     * Get prayer times for a location
     * 
     * @param float $latitude The latitude
     * @param float $longitude The longitude
     * @return array Prayer times
     */
    private function getPrayerTimes($latitude, $longitude) {
        $date = date('d-m-Y');
        $url = PRAYER_API_URL . '/timings/' . $date;
        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 2, // Islamic Society of North America
        ];
        
        $response = $this->apiRequest($url, 'GET', $data);
        
        if (isset($response['data']['timings'])) {
            return $response['data']['timings'];
        }
        
        // Return default times if API fails
        return [
            'Fajr' => '05:00',
            'Dhuhr' => '12:00',
            'Asr' => '15:30',
            'Maghrib' => '18:00',
            'Isha' => '19:30',
        ];
    }
    
    /**
     * Get a random Surah for inspiration
     * 
     * @return array Surah data
     */
    private function getRandomSurah() {
        // There are 114 Surahs in the Quran
        $surahNumber = rand(1, 114);
        
        $url = QURAN_API_URL . '/surah/' . $surahNumber;
        $response = $this->apiRequest($url);
        
        if (isset($response['data'])) {
            return $response['data'];
        }
        
        // Return default if API fails
        return [
            'number' => 1,
            'name' => 'الفاتحة',
            'englishName' => 'Al-Fatiha',
            'englishNameTranslation' => 'The Opening',
            'numberOfAyahs' => 7,
        ];
    }
}
?> 