<?php

/**
 * File: database.php
 * Koneksi Database + Autoloader + Require Semua Class
 */

// ============================================
//  KONFIGURASI DATABASE
// ============================================

define('DB_HOST', 'localhost');
define('DB_NAME', 'DB_UAS_PBO_TI1D_RAFLYARJUNAALMERO');
define('DB_USER', 'root');
define('DB_PASS', '');

// ============================================
//  LOAD SEMUA FILE CLASS (MANUAL)
// ============================================

// Load file class satu per satu (paling aman)
require_once __DIR__ . '/Mahasiswa.php';
require_once __DIR__ . '/MahasiswaMandiri.php';
require_once __DIR__ . '/MahasiswaBidikmisi.php';
require_once __DIR__ . '/MahasiswaPrestasi.php';

// ============================================
//  ATAU PAKAI AUTOLOADER (OTOMATIS)
// ============================================

/*
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});
*/

// ============================================
//  CLASS DATABASE
// ============================================

class Database {
    private static $instance = null;
    private $connection;
    
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
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function __clone() {}
    public function __wakeup() {}
}

/**
 * Fungsi helper untuk mendapatkan koneksi database
 */
function getDB() {
    return Database::getInstance()->getConnection();
}

?>