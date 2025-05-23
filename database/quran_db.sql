-- Quran Website Database Schema

-- Create database
CREATE DATABASE IF NOT EXISTS quran_db;
USE quran_db;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- User preferences table
CREATE TABLE IF NOT EXISTS user_preferences (
    user_id INT PRIMARY KEY,
    theme VARCHAR(20) DEFAULT 'light',
    quran_font VARCHAR(50) DEFAULT 'Amiri',
    quran_font_size INT DEFAULT 22,
    translation_display TINYINT(1) DEFAULT 1,
    tafsir_display TINYINT(1) DEFAULT 0,
    reciter_id INT DEFAULT 1,
    auto_play_audio TINYINT(1) DEFAULT 0,
    notification_daily_verse TINYINT(1) DEFAULT 1,
    notification_prayer_times TINYINT(1) DEFAULT 1,
    notification_newsletters TINYINT(1) DEFAULT 0,
    browser_notifications TINYINT(1) DEFAULT 0,
    last_surah INT DEFAULT 1,
    last_ayah INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bookmarks table
CREATE TABLE IF NOT EXISTS bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    surah_number INT NOT NULL,
    ayah_number INT NOT NULL,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Reading history table
CREATE TABLE IF NOT EXISTS reading_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    surah_number INT NOT NULL,
    ayah_number INT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Chat history table
CREATE TABLE IF NOT EXISTS chat_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_message TEXT NOT NULL,
    bot_message TEXT NOT NULL,
    mood VARCHAR(50),
    recommended_surah INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Reciters table
CREATE TABLE IF NOT EXISTS reciters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    identifier VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample reciters
INSERT INTO reciters (name, identifier) VALUES
('مشاري راشد العفاسي', 'ar.alafasy'),
('عبد الباسط عبد الصمد', 'ar.abdulbasitmurattal'),
('سعد الغامدي', 'ar.saadalghamdi'),
('ماهر المعيقلي', 'ar.mahermuaiqly'),
('محمود خليل الحصري', 'ar.husary');

-- Insert sample user
INSERT INTO users (name, email, password) VALUES
('Ahmed', 'ahmed@example.com', '$2y$10$L3HG2Ww.q17V5K4BVkc5nu5kQnH3Wc.UZtw67BdzfhGBh.tFQxgkO'); -- password: password123

-- Insert sample user preferences
INSERT INTO user_preferences (
    user_id, 
    theme, 
    quran_font, 
    quran_font_size, 
    translation_display, 
    tafsir_display, 
    reciter_id, 
    auto_play_audio
) VALUES
(1, 'light', 'Amiri', 22, 1, 0, 1, 0);

-- Insert sample bookmarks
INSERT INTO bookmarks (user_id, surah_number, ayah_number, name) VALUES
(1, 1, 1, 'الفاتحة'),
(1, 36, 1, 'يس'),
(1, 55, 1, 'الرحمن'),
(1, 67, 1, 'الملك');

-- Insert sample reading history
INSERT INTO reading_history (user_id, surah_number, ayah_number) VALUES
(1, 1, 7),
(1, 36, 15),
(1, 55, 20),
(1, 67, 10);

-- Insert sample chat history
INSERT INTO chat_history (user_id, user_message, bot_message, mood, recommended_surah) VALUES
(1, 'أشعر بالحزن اليوم', 'أشعر أنك حزين اليوم. قد تجد الراحة في قراءة سورة الشرح. تقدم الراحة والطمأنينة في أوقات الحزن', 'sad', 94),
(1, 'أنا قلق بشأن المستقبل', 'يبدو أنك قلق. سورة الرعد قد تساعدك على الشعور بالطمأنينة. تذكر بأن ذكر الله يجلب الطمأنينة للقلوب', 'anxious', 13); 