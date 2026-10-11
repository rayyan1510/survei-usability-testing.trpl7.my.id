
<?php

include_once './database/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // var_dump($_POST);
    // tampung id
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;
    $id_responden = isset($_POST['id_responden']) ? (int) $_POST['id_responden'] : 0;
    $id_sus_list = isset($_POST['id_sus']) ? $_POST['id_sus'] : [];

    // Validasi awal
    if ($id_survei <= 0 || $id_responden <= 0 || empty($id_sus_list)) {
        echo "<script>alert('Data tidak valid.'); window.location.href='./404.php';</script>";
        exit;
    }

    // Cek apakah responden sudah pernah mengisi SUS sebelumnya
    $stmtCek = $conn->prepare("SELECT COUNT(*) FROM hasil_skor_sus WHERE id_responden = ? AND id_survei = ?");
    $stmtCek->bind_param("ii", $id_responden, $id_survei);
    $stmtCek->execute();
    $stmtCek->bind_result($jumlah);
    $stmtCek->fetch();
    $stmtCek->close();

    if ($jumlah > 0) {
        echo "<script>alert('Kamu sudah mengisi survei SUS sebelumnya.'); window.location.href='./404.php';</script>";
        // echo "Kamu sudah mengisi survei SUS sebelumnya.";
        // exit;
    }

    // Persiapkan statement insert
    $stmtInsert = $conn->prepare("INSERT INTO respon_sus (id_responden, id_survei_sus, answer, id_survei, created_at) VALUES (?, ?, ?, ?, NOW())");

    $berhasil = true;

    foreach ($id_sus_list as $id_sus) {
        $no_pertanyaan = "q" . $id_sus;
        $jawaban = isset($_POST[$no_pertanyaan]) ? (int) $_POST[$no_pertanyaan] : null;

        // cek jika jawabannya tidak sesuai 1-5 maka berhentikan
        if (!$jawaban || $jawaban < 1 || $jawaban > 5) {
            $berhasil = false;
            break;
        }

        // echo "$no_pertanyaan jawaban dari responden: " . $jawaban . "\n";

        // lakukan proses insert dengan bind paramaeter dan lakukan pengecekan satu satu data yang di input user
        $stmtInsert->bind_param("iiii", $id_responden, $id_sus, $jawaban, $id_survei);
        if (!$stmtInsert->execute()) {
            $berhasil = false;
            break;
        }
    }

    $stmtInsert->close();

    if ($berhasil) {
        // jika berhasil, jalankan perhitungan sus

        // Ambil data jawaban + is_positif
        $stmtSkor = $conn->prepare("
            SELECT s.id_sus, s.is_positif, r.answer
            FROM respon_sus r
            JOIN v_survei_sus s ON r.id_survei_sus = s.id_sus
            WHERE r.id_responden = ? AND r.id_survei = ?
        ");
        $stmtSkor->bind_param("ii", $id_responden, $id_survei);
        $stmtSkor->execute();
        $resultSkor = $stmtSkor->get_result();

        $totalNilai = 0;

        while ($row = $resultSkor->fetch_assoc()) {
            $jawaban = (int) $row['answer'];
            $isPositif = (int) $row['is_positif'];

            $nilai = ($isPositif === 1) ? ($jawaban - 1) : (5 - $jawaban);
            $totalNilai += $nilai;
            // echo $nilai;
        }

        $stmtSkor->close();

        $totalSkorSUS = $totalNilai * 2.5;

        // Ambil nama responden dari tabel biodata_responden
        $stmtNama = $conn->prepare("SELECT nama_responden FROM biodata_responden WHERE id_responden = ?");
        $stmtNama->bind_param("i", $id_responden);
        $stmtNama->execute();
        $stmtNama->bind_result($nama_responden);
        $stmtNama->fetch();
        $stmtNama->close();


        // Simpan hasil skor SUS
        $stmtHasil = $conn->prepare("
            INSERT INTO hasil_skor_sus (id_survei, id_responden, nama_responden, total_skor_sus)
            VALUES (?, ?, ?, ?)
        ");
        $stmtHasil->bind_param("iisd", $id_survei, $id_responden, $nama_responden, $totalSkorSUS);
        $stmtHasil->execute();
        $stmtHasil->close();

        echo "<script>
                alert('Jawaban Anda berhasil disimpan.');
                window.location.href = './terima-kasih.php';
        </script>";
    } else {
        // jika gagal
        echo "<script>
            alert('Terjadi kesalahan saat menyimpan jawaban. Silakan coba lagi.');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>alert('Metode tidak diizinkan.'); window.location.href='./404.php';</script>";
    exit;
}
