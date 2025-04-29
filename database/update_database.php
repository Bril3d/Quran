<?php
/**
 * Database Update Script
 * This script will update the database schema to support the new profile and settings functionality
 */

// Define the database credentials (you might want to get these from the config file)
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'quran_db';

// Show header
echo "=================================================\n";
echo "Quran Website Database Update Script\n";
echo "=================================================\n\n";

// Connect to the database
echo "Connecting to database...\n";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!\n\n";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

// Read the migration SQL file
$migrationFile = __DIR__ . '/migrate_preferences.sql';
echo "Reading migration file: $migrationFile\n";
if (!file_exists($migrationFile)) {
    die("Migration file not found!\n");
}

$sql = file_get_contents($migrationFile);
$sqlStatements = explode(';', $sql);

// Execute each SQL statement
echo "Executing migration script...\n";
$pdo->beginTransaction();
try {
    $successCount = 0;
    foreach ($sqlStatements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            $pdo->exec($statement);
            $successCount++;
        }
    }
    $pdo->commit();
    echo "Migration completed successfully! ($successCount statements executed)\n";
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
    die("Rollback complete.\n");
}

// Final message
echo "\n=================================================\n";
echo "Database update completed successfully!\n";
echo "Your database is now ready for the profile and settings functionality.\n";
echo "=================================================\n";
?> 