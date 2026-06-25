<?php

/**
 * File: database.php
 * Fungsi: Mengelola koneksi database dan autoloading class
 */

// Set error reporting untuk development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definisi konstanta database
define('DB_HOST', 'localhost');
define('DB_NAME', 'nama_database_anda'); // Ganti dengan nama database Anda
define('DB_USER', 'username_anda');      // Ganti dengan username database Anda
define('DB_PASS', 'password_anda');      // Ganti dengan password database Anda

/**
 * Class Database
 * Mengelola koneksi database menggunakan pattern Singleton
 */
class Database {
    private static $instance = null;
    private $connection;
    
    /**
     * Constructor private untuk Singleton pattern
     */
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
    
    /**
     * Mendapatkan instance tunggal dari Database
     * @return Database
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Mendapatkan koneksi PDO
     * @return PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Mencegah cloning
     */
    private function __clone() {}
    
    /**
     * Mencegah unserialize
     */
    public function __wakeup() {}
}

/**
 * Autoloader untuk memuat class secara otomatis
 */
spl_autoload_register(function ($class_name) {
    // Daftar direktori yang akan dicari
    $directories = [
        __DIR__ . '/classes/',        // Direktori utama class
        __DIR__ . '/models/',         // Direktori models
        __DIR__ . '/',                // Root directory
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
    
    // Jika class tidak ditemukan, tampilkan error
    die("Class '$class_name' tidak ditemukan.");
});

/**
 * Fungsi helper untuk mendapatkan koneksi database
 * @return PDO
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

/*
 * Fungsi helper untuk debugging
 */
function debug($data, $die = false) {
    echo "<pre style='background: #f4f4f4; padding: 10px; border: 1px solid #ddd; margin: 10px;'>";
    print_r($data);
    echo "</pre>";
    if ($die) {
        die();
    }
}

?>