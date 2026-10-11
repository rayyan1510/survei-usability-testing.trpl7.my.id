<?php

$id_login = $_SESSION['id_login'];
$id_survei =  isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;


if ($id_survei <= 0 && $id_login <= 0) {
    # cek jika id_survei dan id$id_login tidak ada, maka redirect halaman
    echo "<script>alert('ID survei tidak valid!'); window.history.back();</script>";
    exit;
}

// Siapkan statement
$stmt = $conn->prepare("DELETE FROM surveys WHERE id = ? AND id_login = ?");
$stmt->bind_param("ii", $id_survei, $id_login);

if ($stmt->execute()) {
    echo "<script>
    alert('Survei Berhasil di Hapus'); window.location.href = '?page=dashboard'
    </script>";
    // exit();
} else {
    echo "<script>
    alert('Survei Gagal di Hapus'); window.location.href = '?page=dashboard'
    </script>";
    // exit();
}

$stmt->close();
