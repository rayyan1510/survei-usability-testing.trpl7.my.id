<?php
include_once './database/database.php';

header('Content-Type: application/json');

// $id_responden = isset($_GET['id_responden']) ? (int)$_GET['id_responden'] : 0;

// if ($id_responden <= 0) {
//     echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
//     exit;
// }

// $query = $conn->prepare("SELECT konfirmasi FROM biodata_responden WHERE id_responden = ?");
// $query->bind_param("i", $id_responden);
// $query->execute();
// $result = $query->get_result();
// $data = $result->fetch_assoc();

// if ($data) {
//     echo json_encode(['status' => 'success', 'konfirmasi' => (int)$data['konfirmasi']]);
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
// }

if (isset($_GET['id_responden'])) {
    $id_responden = (int) $_GET['id_responden'];
    $query = $conn->prepare("SELECT konfirmasi FROM biodata_responden WHERE id_responden = ?");
    $query->bind_param("i", $id_responden);
    $query->execute();
    $result = $query->get_result()->fetch_assoc();

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'konfirmasi' => (int)$result['konfirmasi']
        ]);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
