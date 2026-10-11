<?php

session_start();

include_once './database/database.php';

function redirectWithMessage($type, $title, $message)
{
    $_SESSION['alert'] = [
        'type' => $type,
        'title' => $title,
        'message' => $message
    ];
    header("Location: register.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    # PERSIAPAN...
    $nama = htmlspecialchars(trim($_POST['nama']));
    $nomor_hp = htmlspecialchars(trim($_POST['nomor_hp']));
    $jabatan = htmlspecialchars(trim($_POST['jabatan']));
    $perusahaan = htmlspecialchars(trim($_POST['perusahaan']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    $hak_akses = 'fasilitator';

    // Validasi input sederhana
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirectWithMessage('error', 'Email Tidak Valid', 'Silakan masukkan alamat email yang benar.');
    }

    // Validasi Input Kosong
    if (empty($nama) || empty($nomor_hp) || empty($jabatan) || empty($perusahaan) || empty($password)) {
        redirectWithMessage('warning', 'Registrasi Gagal', 'Semua field wajib diisi.');
    }

    // Cek apakah email sudah terdaftar
    $check = $conn->prepare("SELECT id_login FROM login WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        redirectWithMessage('error', 'Email Terdaftar', 'Email sudah digunakan. Silakan pakai email lain.');
    }
    $check->close();

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan ke tabel login
    $stmt_login = $conn->prepare("INSERT INTO login (email, password, hak_akses) VALUES (?, ?, ?)");
    $stmt_login->bind_param("sss", $email, $hashed_password, $hak_akses);

    if ($stmt_login->execute()) {
        $id_login = $stmt_login->insert_id;
        $stmt_login->close();

        // Simpan ke tabel users
        $stmt_user = $conn->prepare("INSERT INTO users (name, nomor_hp, jabatan, perusahaan, id_login, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt_user->bind_param("ssssi", $nama, $nomor_hp, $jabatan, $perusahaan, $id_login);

        if ($stmt_user->execute()) {
            redirectWithMessage('success', 'Registrasi Berhasil', 'Akun berhasil didaftarkan.');
            $stmt_user->close();
        } else {
            redirectWithMessage('error', 'Gagal Menyimpan Data User', 'Silakan coba lagi.');
        }

        $stmt_user->close();
    } else {
        redirectWithMessage('error', 'Gagal Menyimpan Data Login', 'Silakan coba lagi.');
    }


    $conn->close();
} else {
    redirectWithMessage('error', 'Akses Ditolak', 'Metode tidak diperbolehkan.');
}
