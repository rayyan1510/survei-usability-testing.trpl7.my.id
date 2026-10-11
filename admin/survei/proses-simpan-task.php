<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // var_dump($_POST);

    // Ambil dan validasi ID survei
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;
    if ($id_survei <= 0) {
        die("ID survei tidak valid.");
    }

    $taskSkenarioList = $_POST['taskSkenario'];
    $deskripsiList = $_POST['deskripsiTeksSkenario'];

    // Validasi panjang array harus sama
    if (count($taskSkenarioList) !== count($deskripsiList)) {
        die("Data tidak valid: jumlah task tidak sama dengan deskripsi.");
    }

    // Query tanpa created_at
    $stmt = $conn->prepare("INSERT INTO surveys_task_skenario (task_skenario, description, id_survei) 
                            VALUES (?, ?, ?)");


    if (!$stmt) {
        die("Gagal mempersiapkan statement: " . $conn->error);
    }

    // Bind parameter
    $stmt->bind_param("ssi", $judul, $deskripsi, $id_survei);

    for ($i = 0; $i < count($taskSkenarioList); $i++) {
        $judul = trim($taskSkenarioList[$i]);
        $deskripsi = trim($deskripsiList[$i]);

        // Validasi isi tidak kosong
        if (empty($judul)) continue;

        if (!$stmt->execute()) {
            echo "Gagal menyimpan data ke-$i: " . $stmt->error;
            $stmt->close();
            exit;
        }
    }

    $stmt->close();

    echo "<script>
            alert('Task Skenario berhasil disimpan!');
            window.location.href = '?page=detail-survei&id_survei=$id_survei';
          </script>";
}
