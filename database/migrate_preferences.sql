-- Migration script to update user_preferences table for existing databases

-- Create reciters table if it doesn't exist
CREATE TABLE IF NOT EXISTS reciters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    identifier VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert reciters if table is empty
INSERT INTO reciters (name, identifier)
SELECT * FROM (
    SELECT 'مشاري راشد العفاسي' AS name, 'ar.alafasy' AS identifier UNION ALL
    SELECT 'عبد الباسط عبد الصمد', 'ar.abdulbasitmurattal' UNION ALL
    SELECT 'سعد الغامدي', 'ar.saadalghamdi' UNION ALL
    SELECT 'ماهر المعيقلي', 'ar.mahermuaiqly' UNION ALL
    SELECT 'محمود خليل الحصري', 'ar.husary'
) AS tmp
WHERE NOT EXISTS (SELECT 1 FROM reciters LIMIT 1);

-- Check if columns exist, and if not, add them
SET @dbname = DATABASE();

-- Add quran_font column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'quran_font';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN quran_font VARCHAR(50) DEFAULT "Amiri" AFTER theme',
'SELECT "Column quran_font already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add quran_font_size column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'quran_font_size';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN quran_font_size INT DEFAULT 22 AFTER quran_font',
'SELECT "Column quran_font_size already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add translation_display column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'translation_display';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN translation_display TINYINT(1) DEFAULT 1 AFTER quran_font_size',
'SELECT "Column translation_display already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add tafsir_display column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'tafsir_display';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN tafsir_display TINYINT(1) DEFAULT 0 AFTER translation_display',
'SELECT "Column tafsir_display already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add reciter_id column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'reciter_id';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN reciter_id INT DEFAULT 1 AFTER tafsir_display',
'SELECT "Column reciter_id already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add auto_play_audio column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'auto_play_audio';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN auto_play_audio TINYINT(1) DEFAULT 0 AFTER reciter_id',
'SELECT "Column auto_play_audio already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add notification_daily_verse column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'notification_daily_verse';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN notification_daily_verse TINYINT(1) DEFAULT 1 AFTER auto_play_audio',
'SELECT "Column notification_daily_verse already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add notification_prayer_times column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'notification_prayer_times';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN notification_prayer_times TINYINT(1) DEFAULT 1 AFTER notification_daily_verse',
'SELECT "Column notification_prayer_times already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add notification_newsletters column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'notification_newsletters';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN notification_newsletters TINYINT(1) DEFAULT 0 AFTER notification_prayer_times',
'SELECT "Column notification_newsletters already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add browser_notifications column if it doesn't exist
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'browser_notifications';

SET @query = IF(@columnExists = 0,
'ALTER TABLE user_preferences ADD COLUMN browser_notifications TINYINT(1) DEFAULT 0 AFTER notification_newsletters',
'SELECT "Column browser_notifications already exists" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Update existing records if preferred_reciter column exists
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'preferred_reciter';

SET @query = IF(@columnExists = 1,
'UPDATE user_preferences up
JOIN reciters r ON up.preferred_reciter = r.identifier
SET up.reciter_id = r.id
WHERE up.reciter_id IS NULL AND r.identifier IS NOT NULL',
'SELECT "Column preferred_reciter does not exist" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop preferred_reciter column if it exists
SET @query = IF(@columnExists = 1,
'ALTER TABLE user_preferences DROP COLUMN preferred_reciter',
'SELECT "Column preferred_reciter does not exist to drop" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Convert font_size to quran_font_size if it exists
SET @columnExists = 0;
SELECT 1 INTO @columnExists
FROM information_schema.columns
WHERE table_schema = @dbname
AND table_name = 'user_preferences'
AND column_name = 'font_size';

SET @query = IF(@columnExists = 1,
'UPDATE user_preferences 
SET quran_font_size = ROUND(font_size * 10) + 10
WHERE quran_font_size IS NULL OR quran_font_size = 22',
'SELECT "Column font_size does not exist" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Drop font_size column if it exists
SET @query = IF(@columnExists = 1,
'ALTER TABLE user_preferences DROP COLUMN font_size',
'SELECT "Column font_size does not exist to drop" AS message');
PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt; 