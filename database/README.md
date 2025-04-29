# Database Update for Profile and Settings

This directory contains SQL scripts and utilities to set up and update the database for the Quran website.

## Database Schema

The main schema file is `quran_db.sql`, which contains the complete database structure including tables for:
- Users
- User preferences
- Bookmarks
- Reading history
- Chat history
- Reciters

## Updating an Existing Database

If you already have a database set up and need to update it to support the new profile and settings functionality, follow these steps:

### Option 1: Using the PHP Script (Recommended)

1. Open `update_database.php` and update the database credentials if needed.
2. Run the script from the command line:
   ```
   php update_database.php
   ```
3. The script will apply all necessary changes to your database.

### Option 2: Manual SQL Execution

1. Open your MySQL client (e.g., phpMyAdmin, MySQL Workbench, or command line).
2. Connect to your database.
3. Execute the contents of `migrate_preferences.sql`.

## Creating a New Database

If you're setting up the database for the first time:

1. Open your MySQL client.
2. Create a new database (e.g., `CREATE DATABASE quran_db;`).
3. Execute the contents of `quran_db.sql`.

## Database Changes for Profile and Settings

The new schema includes the following changes:

1. Added new columns to `user_preferences` table:
   - `quran_font`: Font used for displaying Quran text
   - `quran_font_size`: Size of Quran text
   - `translation_display`: Whether to display translation
   - `tafsir_display`: Whether to display tafsir
   - `reciter_id`: ID of the preferred reciter
   - `auto_play_audio`: Whether to autoplay audio
   - Various notification preferences

2. Added new `reciters` table to store available Quran reciters.

3. Removed old columns:
   - `font_size` (replaced by `quran_font_size`)
   - `preferred_reciter` (replaced by `reciter_id`)

## Troubleshooting

If you encounter any issues during the update:

1. Make sure your database user has sufficient privileges.
2. Check database connection settings.
3. Ensure the database already exists before running the migration.
4. If using the PHP script, make sure PHP can execute PDO database operations.

For further assistance, contact the development team. 