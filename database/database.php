<?php

$configFile = __DIR__ . '/config.local.php';

if (!is_file($configFile)) {
    error_log('database/config.local.php tidak ditemukan. Salin dari config.example.php.');
    http_response_code(500);
    die('Konfigurasi aplikasi belum lengkap.');
}

$config = require $configFile;

try {
    $conn = mysqli_connect($config['host'], $config['user'], $config['pass'], $config['name']);
} catch (mysqli_sql_exception $e) {
    $conn = false;
    error_log('Koneksi database gagal: ' . $e->getMessage());
}

if (!$conn) {
    // Detail error hanya masuk log server, tidak ditampilkan ke pengunjung.
    http_response_code(500);
    die('Koneksi database gagal. Silakan coba lagi nanti.');
}

unset($config);
