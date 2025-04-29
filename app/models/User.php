<?php
require_once BASE_PATH . '/app/models/Database.php';

/**
 * User Model
 * Handles user-related database operations
 */
class User {
    private $db;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Find a user by ID
     * 
     * @param int $id The user ID
     * @return array|false User data or false if not found
     */
    public function findById($id) {
        return $this->db->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    /**
     * Find a user by email
     * 
     * @param string $email The user email
     * @return array|false User data or false if not found
     */
    public function findByEmail($email) {
        return $this->db->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }
    
    /**
     * Find a user by remember token
     * 
     * @param string $token The remember token
     * @return array|false User data or false if not found
     */
    public function findByRememberToken($token) {
        return $this->db->fetchOne("SELECT * FROM users WHERE remember_token = ?", [$token]);
    }
    
    /**
     * Create a new user
     * 
     * @param string $name The user name
     * @param string $email The user email
     * @param string $password The user password (plain text)
     * @return int|false The new user ID or false on failure
     */
    public function create($name, $email, $password) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert the user
        $userId = $this->db->insert('users', [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword
        ]);
        
        // Create default user preferences
        if ($userId) {
            $this->db->insert('user_preferences', [
                'user_id' => $userId
            ]);
        }
        
        return $userId;
    }
    
    /**
     * Update a user
     * 
     * @param int $id The user ID
     * @param array $data The data to update
     * @return int The number of affected rows
     */
    public function update($id, $data) {
        // If password is provided, hash it
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->db->update('users', $data, 'id = ?', [$id]);
    }
    
    /**
     * Update user preferences
     * 
     * @param int $userId The user ID
     * @param array $preferences The preferences to update
     * @return int The number of affected rows
     */
    public function updatePreferences($userId, $preferences) {
        // Check if preferences exist for this user
        $existingPrefs = $this->db->fetchOne("SELECT * FROM user_preferences WHERE user_id = ?", [$userId]);
        
        if ($existingPrefs) {
            return $this->db->update('user_preferences', $preferences, 'user_id = ?', [$userId]);
        } else {
            $preferences['user_id'] = $userId;
            $this->db->insert('user_preferences', $preferences);
            return 1;
        }
    }
    
    /**
     * Get user preferences
     * 
     * @param int $userId The user ID
     * @return array|false The preferences or false if not found
     */
    public function getPreferences($userId) {
        return $this->db->fetchOne("SELECT * FROM user_preferences WHERE user_id = ?", [$userId]);
    }
    
    /**
     * Update remember token for a user
     * 
     * @param int $userId The user ID
     * @param string $token The remember token
     * @return int The number of affected rows
     */
    public function updateRememberToken($userId, $token) {
        return $this->db->update('users', ['remember_token' => $token], 'id = ?', [$userId]);
    }
    
    /**
     * Get user bookmarks
     * 
     * @param int $userId The user ID
     * @return array The bookmarks
     */
    public function getBookmarks($userId) {
        return $this->db->fetchAll("SELECT * FROM bookmarks WHERE user_id = ? ORDER BY created_at DESC", [$userId]);
    }
    
    /**
     * Add a bookmark
     * 
     * @param int $userId The user ID
     * @param int $surahNumber The Surah number
     * @param int $ayahNumber The Ayah number
     * @param string $name The bookmark name
     * @return int|false The new bookmark ID or false on failure
     */
    public function addBookmark($userId, $surahNumber, $ayahNumber, $name = null) {
        return $this->db->insert('bookmarks', [
            'user_id' => $userId,
            'surah_number' => $surahNumber,
            'ayah_number' => $ayahNumber,
            'name' => $name
        ]);
    }
    
    /**
     * Delete a bookmark
     * 
     * @param int $bookmarkId The bookmark ID
     * @param int $userId The user ID (for security)
     * @return int The number of affected rows
     */
    public function deleteBookmark($bookmarkId, $userId) {
        return $this->db->delete('bookmarks', 'id = ? AND user_id = ?', [$bookmarkId, $userId]);
    }
    
    /**
     * Add to reading history
     * 
     * @param int $userId The user ID
     * @param int $surahNumber The Surah number
     * @param int $ayahNumber The Ayah number
     * @return int|false The new history ID or false on failure
     */
    public function addReadingHistory($userId, $surahNumber, $ayahNumber) {
        return $this->db->insert('reading_history', [
            'user_id' => $userId,
            'surah_number' => $surahNumber,
            'ayah_number' => $ayahNumber
        ]);
    }
    
    /**
     * Get reading history
     * 
     * @param int $userId The user ID
     * @param int $limit The maximum number of records to return
     * @param int $offset The offset for pagination
     * @return array The reading history
     */
    public function getReadingHistory($userId, $limit = 10, $offset = 0) {
        return $this->db->fetchAll("SELECT * FROM reading_history WHERE user_id = ? ORDER BY timestamp DESC LIMIT ? OFFSET ?", [$userId, $limit, $offset]);
    }
    
    /**
     * Get the total count of reading history entries
     * 
     * @param int $userId The user ID
     * @return int The total count
     */
    public function getReadingHistoryCount($userId) {
        $result = $this->db->fetchOne("SELECT COUNT(*) as count FROM reading_history WHERE user_id = ?", [$userId]);
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Add to chat history
     * 
     * @param int $userId The user ID
     * @param string $userMessage The user message
     * @param string $botMessage The bot message
     * @param string $mood The detected mood
     * @param int $recommendedSurah The recommended Surah
     * @return int|false The new chat history ID or false on failure
     */
    public function addChatHistory($userId, $userMessage, $botMessage, $mood, $recommendedSurah) {
        return $this->db->insert('chat_history', [
            'user_id' => $userId,
            'user_message' => $userMessage,
            'bot_message' => $botMessage,
            'mood' => $mood,
            'recommended_surah' => $recommendedSurah
        ]);
    }
    
    /**
     * Get chat history
     * 
     * @param int $userId The user ID
     * @param int $limit The maximum number of records to return
     * @return array The chat history
     */
    public function getChatHistory($userId, $limit = 10) {
        return $this->db->fetchAll("SELECT * FROM chat_history WHERE user_id = ? ORDER BY created_at DESC LIMIT ?", [$userId, $limit]);
    }
}
?> 