<?php
session_start();

include '../../database/database.php';

// Validasi input
if (!isset($_POST['id_survei']) || !isset($_POST['status'])) {
    http_response_code(400);
    echo 'Data tidak lengkap.';
    exit;
}

$id_survei = (int) $_POST['id_survei'];
$status = $_POST['status'];
$id_login = $_SESSION['id_login'] ?? 0;

// Validasi status yang diperbolehkan
$allowed_status = ['draft', 'published', 'closed'];
if (!in_array($status, $allowed_status)) {
    http_response_code(400);
    echo 'Status tidak valid.';
    exit;
}

// Pastikan koneksi database berhasil
if (!$conn) {
    http_response_code(500);
    echo 'Koneksi database gagal.';
    exit;
}

// Update ke database
$sql = "UPDATE surveys SET status = ? WHERE id = ? AND id_login = ?";
$stmt = $conn->prepare($sql);

// Cek jika gagal menyiapkan query
if (!$stmt) {
    http_response_code(500);
    echo 'Gagal menyiapkan query.';
    exit;
}

$stmt->bind_param("sii", $status, $id_survei, $id_login);

if ($stmt->execute()) {
    echo 'Status berhasil diperbarui.';
} else {
    http_response_code(500);
    echo 'Gagal memperbarui status.';
}

$stmt->close();
$conn->close();
