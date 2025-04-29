<?php
require_once BASE_PATH . '/app/controllers/Controller.php';

/**
 * Audio Controller
 * Handles the Quran audio recitations
 */
class AudioController extends Controller {
    /**
     * Display the audio player page
     * 
     * @return void
     */
    public function index() {
        // Get the list of Surahs
        $surahs = $this->getSurahs();
        
        // Default to first Surah if none selected
        $surahNumber = $this->get('surah', 1);
        
        // Get available reciters
        $reciters = $this->getReciters();
        
        // Default reciter
        $reciter = $this->get('reciter', 'ar.alafasy');
        
        // Get audio for the selected Surah
        $audioData = $this->getSurahAudio($surahNumber, $reciter);
        
        // Render the audio player view
        $this->render('audio/index', [
            'surahs' => $surahs,
            'surahNumber' => $surahNumber,
            'reciters' => $reciters,
            'selectedReciter' => $reciter,
            'audioData' => $audioData
        ]);
    }
    
    /**
     * Get the list of all Surahs
     * 
     * @return array List of Surahs
     */
    private function getSurahs() {
        $url = QURAN_API_URL . '/surah';
        $response = $this->apiRequest($url);
        
        if (isset($response['data'])) {
            return $response['data'];
        }
        
        return [];
    }
    
    /**
     * Get available Quran reciters
     * 
     * @return array List of reciters
     */
    private function getReciters() {
        return [
            'ar.alafasy' => 'Mishary Rashid Alafasy',
            'ar.abdulbasitmurattal' => 'Abdul Basit Abdul Samad',
            'ar.abdurrahmaansudais' => 'Abdur-Rahman As-Sudais',
            'ar.minshawi' => 'Mohamed Siddiq Al-Minshawi',
            'ar.muhammadayyoub' => 'Muhammad Ayyoub',
        ];
    }
    
    /**
     * Get audio data for a specific Surah
     * 
     * @param int $surahNumber The Surah number
     * @param string $reciter The reciter edition
     * @return array The audio data
     */
    private function getSurahAudio($surahNumber, $reciter) {
        $url = QURAN_API_URL . '/surah/' . $surahNumber . '/' . $reciter;
        $response = $this->apiRequest($url);
        
        if (!isset($response['data'])) {
            return null;
        }
        
        $audioData = $response['data'];
        
        // Format the data for the audio player
        $formattedData = [
            'surah' => [
                'number' => $audioData['number'],
                'name' => $audioData['name'],
                'englishName' => $audioData['englishName'],
                'englishNameTranslation' => $audioData['englishNameTranslation'],
            ],
            'ayahs' => []
        ];
        
        // Extract audio URLs for each ayah
        if (isset($audioData['ayahs'])) {
            foreach ($audioData['ayahs'] as $ayah) {
                $formattedData['ayahs'][] = [
                    'number' => $ayah['number'],
                    'text' => $ayah['text'],
                    'audioUrl' => $ayah['audio'],
                    'numberInSurah' => $ayah['numberInSurah']
                ];
            }
        }
        
        return $formattedData;
    }
    
    /**
     * Download the entire Surah audio
     * 
     * @return void Sets headers and outputs audio file
     */
    public function downloadSurah() {
        $surahNumber = $this->get('surah');
        $reciter = $this->get('reciter', 'ar.alafasy');
        
        if (!$surahNumber) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Missing surah parameter']);
            return;
        }
        
        // Get Surah details to set the file name
        $url = QURAN_API_URL . '/surah/' . $surahNumber;
        $response = $this->apiRequest($url);
        
        if (!isset($response['data'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Surah not found']);
            return;
        }
        
        $surahName = $response['data']['englishName'];
        
        // Redirect to the audio file
        $audioUrl = 'https://download.quranicaudio.com/quran/' . str_replace('ar.', '', $reciter) . '/' . str_pad($surahNumber, 3, '0', STR_PAD_LEFT) . '.mp3';
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'downloadUrl' => $audioUrl,
            'fileName' => $surahName . '.mp3'
        ]);
    }
}
?> 