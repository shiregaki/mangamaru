<?php

// Pastikan file autoload dari composer dipanggil
// Sesuaikan path jika letak file ini berbeda folder
require_once __DIR__ . '/../../vendor/autoload.php';

use Medoo\Medoo;

try {
    $database = new Medoo([
        // Wajib ada
        'type' => 'mysql',
        'host' => 'localhost',
        'database' => 'data_manga',
        'username' => 'root',
        'password' => '', // Default Laragon biasanya kosong

        // Opsional
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_general_ci',
        'port' => 3306,
        
        // Error mode agar mudah debugging saat pengembangan
        'error' => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    echo "Koneksi Gagal: " . $e->getMessage();
    exit;
}