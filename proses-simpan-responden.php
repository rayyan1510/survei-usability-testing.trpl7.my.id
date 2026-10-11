<?php

header('Content-Type: application/json');

include_once './database/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil dan validasi ID survei
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;

    // if ($id_survei <= 0) {
    //     echo "<script>alert('Survei tidak ditemukan!'); window.location.href='./404.php';</script>";
    // }
    if ($id_survei <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Survei tidak ditemukan']);
        exit;
    }


    // Tangkap data dari parameter POST
    $nama = htmlspecialchars($_POST['nama']);
    $pekerjaan = htmlspecialchars($_POST['pekerjaan']);
    $umur = htmlspecialchars($_POST['umur']);


    // mengecek apakah user sudah menekan checkbox setuju persyataran
    $setuju = isset($_POST['setuju']) ? $_POST['setuju'] : null;
    // if ($setuju !== "on") {
    //     # cek jika id_survei tidak ada, maka redirect halaman
    //     echo "<script>alert('Kamu belum melakukan persetujuan!'); window.location.href='./indek.php?id=$id_survei';</script>";
    //     exit;
    // }

    if ($setuju !== "on") {
        # cek jika tidak setuju, maka redirect halaman kemabali ke form data diri responden
        echo json_encode(['status' => 'error', 'message' => 'Persetujuan belum diberikan.']);
        exit;
    }

    // inisialisasi konfirmasi
    $konfirmasi = 0;

    // Query insert tanpa created_at
    $stmt = $conn->prepare("INSERT INTO biodata_responden (nama_responden, umur, pekerjaan, id_survei, konfirmasi) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssii", $nama, $umur, $pekerjaan, $id_survei, $konfirmasi);

    if ($stmt->execute()) {
        $id_responden_baru = $stmt->insert_id; // ambil ID survei yang baru dibuat 
        // echo "
        //     <script>
        //        alert('Selamat kamu telah berhasil input data diri'); window.location.href = './form-sus.php?id_survei=$id_survei&id_responden=$id_responden_baru';
        //     </script>
        //     ";
        // exit;

        // jika sukses
        // $id_responden_baru = $stmt->insert_id;
        echo json_encode([
            'status' => 'success',
            'id_survei' => $id_survei,
            'id_responden' => $id_responden_baru
        ]);
        exit;
    } else {
        // $error = $stmt->error; //" . addslashes($error) . "

        // echo "
        //     <script>
        //        alert('gagal, $error'); window.location.href = './indek.php?id=$id_survei';
        //     </script>
        //     ";
        echo json_encode([
            'status' => 'error',
            'message' => $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
}
