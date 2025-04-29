<?php
require_once BASE_PATH . '/app/controllers/Controller.php';

/**
 * Prayer Controller
 * Handles prayer times functionality
 */
class PrayerController extends Controller {
    /**
     * Display the prayer times page
     * 
     * @return void
     */
    public function index() {
        // Get user's location from cookies or default to Mecca
        $latitude = isset($_COOKIE['latitude']) ? $_COOKIE['latitude'] : 21.3891;
        $longitude = isset($_COOKIE['longitude']) ? $_COOKIE['longitude'] : 39.8579;
        $city = isset($_COOKIE['city']) ? $_COOKIE['city'] : 'Makkah';
        $country = isset($_COOKIE['country']) ? $_COOKIE['country'] : 'Saudi Arabia';
        
        // Get prayer times for today
        $todayPrayerTimes = $this->getPrayerTimes($latitude, $longitude);
        
        // Get prayer times for the whole month
        $monthPrayerTimes = $this->getMonthPrayerTimes($latitude, $longitude);
        
        // Render the prayer times view
        $this->render('prayer/index', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'city' => $city,
            'country' => $country,
            'todayPrayerTimes' => $todayPrayerTimes,
            'monthPrayerTimes' => $monthPrayerTimes
        ]);
    }
    
    /**
     * Get prayer times for a specific location and date
     * 
     * @param float $latitude The latitude
     * @param float $longitude The longitude
     * @param string $date The date (dd-mm-yyyy)
     * @return array Prayer times
     */
    private function getPrayerTimes($latitude, $longitude, $date = null) {
        if ($date === null) {
            $date = date('d-m-Y');
        }
        
        $url = PRAYER_API_URL . '/timings/' . $date;
        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 2, // Islamic Society of North America
        ];
        
        $response = $this->apiRequest($url, 'GET', $data);
        
        if (isset($response['data'])) {
            return [
                'date' => $response['data']['date']['readable'],
                'hijri' => $response['data']['date']['hijri']['date'],
                'hijriMonth' => $response['data']['date']['hijri']['month']['ar'],
                'timings' => $response['data']['timings']
            ];
        }
        
        // Return default times if API fails
        return [
            'date' => date('d M Y'),
            'hijri' => '',
            'hijriMonth' => '',
            'timings' => [
                'Fajr' => '05:00',
                'Sunrise' => '06:15',
                'Dhuhr' => '12:00',
                'Asr' => '15:30',
                'Sunset' => '18:00',
                'Maghrib' => '18:15',
                'Isha' => '19:30',
                'Imsak' => '04:50',
                'Midnight' => '00:00',
            ]
        ];
    }
    
    /**
     * Get prayer times for an entire month
     * 
     * @param float $latitude The latitude
     * @param float $longitude The longitude
     * @param int $month The month (1-12)
     * @param int $year The year
     * @return array Monthly prayer times
     */
    private function getMonthPrayerTimes($latitude, $longitude, $month = null, $year = null) {
        if ($month === null) {
            $month = date('m');
        }
        
        if ($year === null) {
            $year = date('Y');
        }
        
        $url = PRAYER_API_URL . '/calendar/' . $year . '/' . $month;
        $data = [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'method' => 2, // Islamic Society of North America
        ];
        
        $response = $this->apiRequest($url, 'GET', $data);
        
        if (isset($response['data'])) {
            return $response['data'];
        }
        
        return [];
    }
    
    /**
     * API endpoint for getting prayer times based on coordinates
     * 
     * @return void Outputs JSON response
     */
    public function getPrayerTimesByCoordinates() {
        $latitude = $this->post('latitude');
        $longitude = $this->post('longitude');
        $date = $this->post('date');
        
        if (!$latitude || !$longitude) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing latitude or longitude']);
            return;
        }
        
        $prayerTimes = $this->getPrayerTimes($latitude, $longitude, $date);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $prayerTimes
        ]);
    }
    
    /**
     * API endpoint for getting prayer times based on city
     * 
     * @return void Outputs JSON response
     */
    public function getPrayerTimesByCity() {
        $city = $this->post('city');
        $country = $this->post('country');
        $date = $this->post('date');
        
        if (!$city) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing city']);
            return;
        }
        
        // Get coordinates for the city
        $coordinates = $this->getCityCoordinates($city, $country);
        
        if (!$coordinates) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'City not found']);
            return;
        }
        
        $prayerTimes = $this->getPrayerTimes($coordinates['latitude'], $coordinates['longitude'], $date);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'data' => $prayerTimes,
            'coordinates' => $coordinates
        ]);
    }
    
    /**
     * Get coordinates for a city
     * 
     * @param string $city The city name
     * @param string $country The country name
     * @return array|null Coordinates or null
     */
    private function getCityCoordinates($city, $country = null) {
        // For simplicity, this is a mock function
        // In a real app, this would use a geocoding API
        
        $cities = [
            'makkah' => ['latitude' => 21.3891, 'longitude' => 39.8579],
            'madinah' => ['latitude' => 24.5247, 'longitude' => 39.5692],
            'riyadh' => ['latitude' => 24.7136, 'longitude' => 46.6753],
            'jeddah' => ['latitude' => 21.4858, 'longitude' => 39.1925],
            'cairo' => ['latitude' => 30.0444, 'longitude' => 31.2357],
            'istanbul' => ['latitude' => 41.0082, 'longitude' => 28.9784],
            'dubai' => ['latitude' => 25.2048, 'longitude' => 55.2708],
            'london' => ['latitude' => 51.5074, 'longitude' => -0.1278],
            'new york' => ['latitude' => 40.7128, 'longitude' => -74.0060],
        ];
        
        $cityLower = strtolower($city);
        
        if (isset($cities[$cityLower])) {
            return $cities[$cityLower];
        }
        
        // Default to Makkah if city not found
        return ['latitude' => 21.3891, 'longitude' => 39.8579];
    }
}
?> 