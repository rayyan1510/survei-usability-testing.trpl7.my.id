<?php

$id_login = $_SESSION['id_login'];
$id_survei =  isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;
$id_task =  isset($_GET['id_task']) ? (int) $_GET['id_task'] : 0;

if ($id_survei <= 0 && $id_task <= 0) {
    # cek jika id_survei dan id_task tidak ada, maka redirect halaman
    echo "<script>alert('ID survei dan ID Task Skenario tidak valid!'); window.history.back();</script>";
    exit;
}

// Siapkan statement
$stmt = $conn->prepare("DELETE FROM surveys_task_skenario WHERE id = ? AND id_survei = ?");
$stmt->bind_param("ii", $id_task, $id_survei);

if ($stmt->execute()) {
    echo "<script>
    alert('Data Task Skenario Berhasil di Hapus'); window.location.href = '?page=detail-survei&id_survei=$id_survei'
    </script>";
    // exit();
} else {
    echo "<script>
    alert('Data Task Skenario Gagal di Hapus'); window.location.href = '?page=detail-survei&id_survei=$id_survei'
    </script>";
    // exit();
}

$stmt->close();
