<?php

session_start();

include_once './database/database.php';

function alertAndRedirect($type, $title, $message, $location = 'login.php')
{
    $_SESSION['alert'] = [
        'type' => $type,
        'title' => $title,
        'message' => $message
    ];
    header("Location: $location");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Persiapan
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validasi input sederhana
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password)) {
        echo "email atau password tidak boleh kosong";
        alertAndRedirect('error', 'Login Gagal', 'Email atau Password tidak boleh kosong.');
    }

    $stmt = $conn->prepare("SELECT id_login, password, hak_akses FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id_login, $hashed_password, $hak_akses);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['login'] = true;
            $_SESSION['id_login'] = $id_login;
            $_SESSION['hak_akses'] = $hak_akses;

            alertAndRedirect('success', 'Login Berhasil', 'Selamat datang!', './admin/index.php');
        } else {
            alertAndRedirect('error', 'Password Salah', 'Coba lagi.');
        }
    } else {

        alertAndRedirect('error', 'Akun Tidak Ditemukan', 'Email tidak terdaftar.');
    }

    $stmt->close();
    $conn->close();
} else {
    alertAndRedirect('error', 'Akses Ditolak', 'Metode tidak diizinkan.');
}
