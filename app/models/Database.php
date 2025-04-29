<?php
/**
 * Database Model
 * Handles database connection and operations
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Constructor - Private to prevent direct instantiation
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die('Database connection error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get Database instance (Singleton pattern)
     * 
     * @return Database The Database instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        
        return self::$instance;
    }
    
    /**
     * Get the PDO connection
     * 
     * @return PDO The PDO connection
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Execute a query with parameters
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return PDOStatement The PDO statement
     */
    public function query($query, $params = []) {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die('Query error: ' . $e->getMessage());
        }
    }
    
    /**
     * Fetch a single row
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array|false The fetched row or false if no rows
     */
    public function fetchOne($query, $params = []) {
        $stmt = $this->query($query, $params);
        return $stmt->fetch();
    }
    
    /**
     * Fetch all rows
     * 
     * @param string $query The SQL query
     * @param array $params The query parameters
     * @return array The fetched rows
     */
    public function fetchAll($query, $params = []) {
        $stmt = $this->query($query, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Insert a row into a table
     * 
     * @param string $table The table name
     * @param array $data The data to insert (column => value)
     * @return int|false The last insert ID or false on failure
     */
    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        
        $this->query($query, array_values($data));
        return $this->connection->lastInsertId();
    }
    
    /**
     * Update rows in a table
     * 
     * @param string $table The table name
     * @param array $data The data to update (column => value)
     * @param string $where The WHERE clause
     * @param array $params The WHERE clause parameters
     * @return int The number of affected rows
     */
    public function update($table, $data, $where, $params = []) {
        $set = [];
        
        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
        }
        
        $set = implode(', ', $set);
        
        $query = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        $stmt = $this->query($query, array_merge(array_values($data), $params));
        return $stmt->rowCount();
    }
    
    /**
     * Delete rows from a table
     * 
     * @param string $table The table name
     * @param string $where The WHERE clause
     * @param array $params The WHERE clause parameters
     * @return int The number of affected rows
     */
    public function delete($table, $where, $params = []) {
        $query = "DELETE FROM {$table} WHERE {$where}";
        
        $stmt = $this->query($query, $params);
        return $stmt->rowCount();
    }
}
?> 