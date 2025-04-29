<?php
require_once BASE_PATH . '/app/controllers/Controller.php';
require_once BASE_PATH . '/app/models/User.php';

/**
 * Chat Controller
 * Handles the mood-based Surah recommendations
 */
class ChatController extends Controller {
    private $userModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->userModel = new User();
    }
    
    /**
     * Display the chat interface
     * 
     * @return void
     */
    public function index() {
        // Get chat history if user is logged in
        $chatHistory = [];
        if (isLoggedIn()) {
            $chatHistory = $this->userModel->getChatHistory(getCurrentUserId(), 5);
        }
        
        $this->render('chat/index', [
            'chatHistory' => $chatHistory
        ]);
    }
    
    /**
     * Process a new chat message and return a recommendation
     * 
     * @return void Outputs JSON response
     */
    public function getRecommendation() {
        $message = $this->post('message');
        
        if (!$message) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No message provided']);
            return;
        }
        
        // Get mood from message
        $mood = $this->analyzeMood($message);
        
        // Get recommended Surah based on mood
        $recommendation = $this->getSurahRecommendation($mood);
        
        // Save to chat history if user is logged in
        if (isLoggedIn()) {
            $this->userModel->addChatHistory(
                getCurrentUserId(),
                $message,
                $recommendation['message'],
                $mood,
                $recommendation['number']
            );
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'mood' => $mood,
            'recommendation' => $recommendation
        ]);
    }
    
    /**
     * Analyze the mood of a message
     * 
     * @param string $message The user message
     * @return string The detected mood
     */
    private function analyzeMood($message) {
        // This is a simple keyword-based mood detection
        // In a production app, this could use more advanced NLP
        
        $message = strtolower($message);
        
        $moodKeywords = [
            'sad' => ['sad', 'depressed', 'unhappy', 'down', 'حزين', 'حزن', 'مكتئب'],
            'anxious' => ['anxious', 'worried', 'nervous', 'stress', 'قلق', 'متوتر', 'ضغط'],
            'happy' => ['happy', 'joy', 'excited', 'سعيد', 'فرح', 'مبتهج'],
            'grateful' => ['grateful', 'thankful', 'blessed', 'شكر', 'ممتن', 'نعمة'],
            'confused' => ['confused', 'lost', 'unsure', 'حيرة', 'تائه', 'مرتبك'],
            'seeking' => ['guidance', 'help', 'direction', 'هداية', 'مساعدة', 'توجيه'],
            'peaceful' => ['peace', 'calm', 'tranquil', 'سلام', 'هدوء', 'سكينة']
        ];
        
        foreach ($moodKeywords as $mood => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($message, $keyword) !== false) {
                    return $mood;
                }
            }
        }
        
        // Default mood if no keywords matched
        return 'seeking';
    }
    
    /**
     * Get a Surah recommendation based on mood
     * 
     * @param string $mood The detected mood
     * @return array The recommendation
     */
    private function getSurahRecommendation($mood) {
        // Map moods to recommended Surahs
        $moodRecommendations = [
            'sad' => [
                ['number' => 94, 'name' => 'الشرح', 'englishName' => 'Ash-Sharh', 'reason' => 'تقدم الراحة والطمأنينة في أوقات الحزن'],
                ['number' => 93, 'name' => 'الضحى', 'englishName' => 'Ad-Duhaa', 'reason' => 'تذكرنا بأن الله لا يتخلى عنا أبدًا'],
                ['number' => 2, 'name' => 'البقرة', 'englishName' => 'Al-Baqarah', 'reason' => 'آيات 153-157 تقدم تعزية للمحزون'],
            ],
            'anxious' => [
                ['number' => 13, 'name' => 'الرعد', 'englishName' => 'Ar-Ra\'d', 'reason' => 'تذكر بأن ذكر الله يجلب الطمأنينة للقلوب'],
                ['number' => 48, 'name' => 'الفتح', 'englishName' => 'Al-Fath', 'reason' => 'تبعث على الثقة والاطمئنان'],
                ['number' => 9, 'name' => 'التوبة', 'englishName' => 'At-Tawbah', 'reason' => 'تذكر بأن الله هو الحافظ والمعين'],
            ],
            'happy' => [
                ['number' => 55, 'name' => 'الرحمن', 'englishName' => 'Ar-Rahman', 'reason' => 'تذكر بنعم الله الكثيرة'],
                ['number' => 56, 'name' => 'الواقعة', 'englishName' => 'Al-Waqi\'ah', 'reason' => 'تزيد من الشكر والامتنان'],
                ['number' => 19, 'name' => 'مريم', 'englishName' => 'Maryam', 'reason' => 'تحكي قصص رحمة الله بعباده'],
            ],
            'grateful' => [
                ['number' => 55, 'name' => 'الرحمن', 'englishName' => 'Ar-Rahman', 'reason' => 'تعدد نعم الله التي تستحق الشكر'],
                ['number' => 14, 'name' => 'إبراهيم', 'englishName' => 'Ibrahim', 'reason' => 'تذكر بضرورة شكر النعم'],
                ['number' => 31, 'name' => 'لقمان', 'englishName' => 'Luqman', 'reason' => 'تحث على الشكر والحكمة'],
            ],
            'confused' => [
                ['number' => 18, 'name' => 'الكهف', 'englishName' => 'Al-Kahf', 'reason' => 'تقدم قصصًا عن الصبر والإيمان في مواجهة الشك'],
                ['number' => 10, 'name' => 'يونس', 'englishName' => 'Yunus', 'reason' => 'توضح أن الهداية تأتي من الله'],
                ['number' => 27, 'name' => 'النمل', 'englishName' => 'An-Naml', 'reason' => 'تبين حكمة الله في خلقه'],
            ],
            'seeking' => [
                ['number' => 1, 'name' => 'الفاتحة', 'englishName' => 'Al-Fatihah', 'reason' => 'دعاء للهداية إلى الصراط المستقيم'],
                ['number' => 18, 'name' => 'الكهف', 'englishName' => 'Al-Kahf', 'reason' => 'تحتوي على قصص الهداية والتوجيه'],
                ['number' => 36, 'name' => 'يس', 'englishName' => 'Ya-Sin', 'reason' => 'تقدم توجيهات للحياة المؤمنة'],
            ],
            'peaceful' => [
                ['number' => 36, 'name' => 'يس', 'englishName' => 'Ya-Sin', 'reason' => 'تبعث على السكينة والطمأنينة'],
                ['number' => 67, 'name' => 'الملك', 'englishName' => 'Al-Mulk', 'reason' => 'تذكر بقدرة الله وعظمته'],
                ['number' => 33, 'name' => 'الأحزاب', 'englishName' => 'Al-Ahzab', 'reason' => 'تقدم نموذجًا للحياة المطمئنة'],
            ]
        ];
        
        // Get recommendations for the mood
        $recommendations = isset($moodRecommendations[$mood]) ? $moodRecommendations[$mood] : $moodRecommendations['seeking'];
        
        // Select a random recommendation from the list
        $recommendation = $recommendations[array_rand($recommendations)];
        
        // Get more details about the recommended Surah
        $surahUrl = QURAN_API_URL . '/surah/' . $recommendation['number'];
        $surahResponse = $this->apiRequest($surahUrl);
        
        if (isset($surahResponse['data'])) {
            $recommendation['details'] = $surahResponse['data'];
        }
        
        // Create response message
        $responseMessages = [
            'sad' => 'أشعر أنك حزين اليوم. قد تجد الراحة في قراءة سورة %s. %s',
            'anxious' => 'يبدو أنك قلق. سورة %s قد تساعدك على الشعور بالطمأنينة. %s',
            'happy' => 'سعيد لرؤيتك سعيدًا! سورة %s ستزيد من سعادتك. %s',
            'grateful' => 'الشكر نعمة رائعة. سورة %s تذكرنا بنعم الله الكثيرة. %s',
            'confused' => 'عندما نشعر بالحيرة، يمكن أن تقدم سورة %s الإرشاد. %s',
            'seeking' => 'الباحث عن الهداية سيجدها. أنصحك بقراءة سورة %s. %s',
            'peaceful' => 'للحفاظ على هذا السلام الداخلي، اقرأ سورة %s. %s'
        ];
        
        $messageTemplate = isset($responseMessages[$mood]) ? $responseMessages[$mood] : $responseMessages['seeking'];
        $recommendation['message'] = sprintf($messageTemplate, $recommendation['name'], $recommendation['reason']);
        
        return $recommendation;
    }
}
?> 