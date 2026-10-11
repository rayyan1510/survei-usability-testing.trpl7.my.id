<?php
// cek sesi login
if (!isset($_SESSION['id_login'])) {
    header("Location: ../../login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Ambil dan validasi ID survei dan Id Responden
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;
    $id_responden = isset($_POST['id_responden']) ? (int) $_POST['id_responden'] : 0;
    if ($id_survei <= 0 && $id_responden <= 0) {
        die("ID tidak valid.");
    }

    $id_task_skenario_list = $_POST['id_task_skenario'];
    $waktuPenyelesaian_list = $_POST['waktuPenyelesaian'];
    $jumlahError_list = $_POST['jumlahError'];
    $sukses_gagal = $_POST['sukses-gagal'];
    $catatanObservasi = $_POST['catatanObservasi'];

    // ngatur status untuk berhenti looping
    $berhasil = true;

    // Looping masing-masing task yang telah di input catatannya
    for ($i = 0; $i < count($id_task_skenario_list); $i++) {
        # code...
        $taskId = $id_task_skenario_list[$i];
        $waktu = $waktuPenyelesaian_list[$i];
        $error = $jumlahError_list[$i];
        $catatan = $catatanObservasi[$i];

        // Konversi status dari string ke integer
        $status_str = isset($sukses_gagal[$i + 1]) ? $sukses_gagal[$i + 1] : 'gagal';
        $status = ($status_str === 'sukses') ? 1 : 0;

        $sql = "INSERT INTO respon_task_skenario
                (id_responden, time_completion, task_sukses, number_errors, catatan, id_survei, id_task)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiisii", $id_responden, $waktu, $status, $error, $catatan, $id_survei, $taskId);

        // cek jika ada permasalahan ketika proses insert dan looping berhenti
        if (!$stmt->execute()) {
            $berhasil = false;
            break;
        }
    }

    // cek apakah proses insert data task skenario catatan, dan variabel $berhasil
    if ($berhasil) {
        // jika true maka lakukan proses update status bahwa survei responden telah di konfirmasi
        $sqlKonfirmasi = "UPDATE biodata_responden SET konfirmasi = 1 WHERE id_responden = ?";
        $stmtKonfirmasi = $conn->prepare($sqlKonfirmasi);
        $stmtKonfirmasi->bind_param("i", $id_responden);

        if ($stmtKonfirmasi->execute()) {
            // jika true, redirect ke halaman detail responden
            echo "<script>
                alert('Data berhasil disimpan dan responden dikonfirmasi!');
                // window.location.href = '?page=detail-responden&id=$id_responden';
                window.location.href = '?page=detail-survei&id_survei=$id_survei';
            </script>";
        } else {
            // jika false tampilkan pesan gagal konfirmasi responden
            echo "<script>
                alert('Data catatan berhasil, tetapi gagal mengkonfirmasi responden.');
                window.history.back();
            </script>";
        }
    } else {
        // jika false redirect kembali
        echo "<script>
            alert('Terjadi kesalahan saat menyimpan data.');
            window.history.back();
        </script>";
    }
} else {
    // Jika ada yang maksa kirim maka lempar
    echo "<script>
        alert('Akses tidak valid!');
        window.history.back();
    </script>";
}
