<?php
session_start();
include '../../database/database.php'; // pastikan koneksi DB disertakan

header('Content-Type: application/json');

$id_login = $_SESSION['id_login'];
$id_survei = $_GET['id_survei'] ?? 0;

if (!$id_survei) {
    echo json_encode(['error' => 'ID survei tidak valid']);
    exit;
}

// Query data responden
$sqlResponden = "SELECT * FROM biodata_responden WHERE id_survei = ? ORDER by id_responden DESC";
$stmt = $conn->prepare($sqlResponden);
$stmt->bind_param("i", $id_survei);
$stmt->execute();
$result = $stmt->get_result();

$respondenList = [];
$totalResponden = 0;
$terkonfirmasi = 0;
$belumKonfirmasi = 0;

while ($row = $result->fetch_assoc()) {
    $totalResponden++;
    if ($row['konfirmasi'] == 1) $terkonfirmasi++;
    else $belumKonfirmasi++;

    $tanggalRespon = new DateTime($row['created_at']);
    $waktuFormatted = $tanggalRespon->format('d-m-Y H:i');
    $status = ($row['konfirmasi'] == 1) ? '✅ Sudah' : '⏳ Belum';

    $respondenList[] = [
        'id_responden' => $row['id_responden'],
        'nama_responden' => htmlspecialchars($row['nama_responden']),
        'waktu' => $waktuFormatted,
        'status' => $status,
        'konfirmasi' => (int)$row['konfirmasi']
    ];
}

echo json_encode([
    'total' => $totalResponden,
    'terkonfirmasi' => $terkonfirmasi,
    'belumKonfirmasi' => $belumKonfirmasi,
    'responden' => $respondenList,
]);
