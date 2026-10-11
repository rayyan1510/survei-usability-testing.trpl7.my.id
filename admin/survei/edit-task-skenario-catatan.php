<?php


$id_login = htmlspecialchars($_SESSION['id_login']);

$id_task_skenario =  htmlspecialchars($_GET['id_task']);
$id_responden = htmlspecialchars($_GET['id_responden']);
$id_survei = htmlspecialchars($_GET['id_survei']);

// ==========================================================
// Query untuk ambil data task skenario per id
$sqlTaskSkenario = "SELECT * FROM surveys_task_skenario WHERE id_survei = ? AND id = ?";
$stmtTaskSkenario = $conn->prepare($sqlTaskSkenario);
$stmtTaskSkenario->bind_param("ii", $id_survei, $id_task_skenario);
$stmtTaskSkenario->execute();
$resultTaskSkenario = $stmtTaskSkenario->get_result();

// cek jika hasilnya kosong
if ($resultTaskSkenario->num_rows === 0) {
    // Redirect kembali dan tampilkan pesan
    echo "<script>alert('Data task skenario tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$data = $resultTaskSkenario->fetch_assoc();

// ===================================================
// Query untuk menampilkan task skenario catatan hasil observasi "respon dari responden"
$sqlTaskSkenarioRespon = "SELECT * FROM respon_task_skenario WHERE id_survei = ? AND id_task = ? AND id_responden = ?";
$stmtTaskSkenarioRespon = $conn->prepare($sqlTaskSkenarioRespon);
$stmtTaskSkenarioRespon->bind_param("iii", $id_survei, $id_task_skenario, $id_responden);
$stmtTaskSkenarioRespon->execute();
$resultTaskSkenarioRespon = $stmtTaskSkenarioRespon->get_result();

// cek jika hasilnya kosong
if ($resultTaskSkenarioRespon->num_rows === 0) {
    // Redirect kembali dan tampilkan pesan
    echo "<script>alert('Data respon task skenario tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$dataResponTask = $resultTaskSkenarioRespon->fetch_assoc();

// ==================================================
// Proses Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ambil data yang dikirim dan validasi ID survei dan Id Responden
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;
    $id_responden = isset($_POST['id_responden']) ? (int) $_POST['id_responden'] : 0;
    $id_respon_task = isset($_POST['id_respon_task']) ? (int) $_POST['id_respon_task'] : 0;

    // cek apakah kosong atau tidak
    if ($id_survei <= 0 && $id_responden <= 0) {
        die("ID tidak valid.");
    }

    $waktuPenyelesaian = $_POST['waktuPenyelesaian'];
    $jumlahError = $_POST['jumlahError'];

    // Konversi sukses atau gagal task dari string menjadi int
    $sukses_gagal = ($_POST['sukses-gagal'] === 'sukses') ? 1 : 0;
    $catatanObservasi = $_POST['catatanObservasi'];

    // var_dump($sukses_gagal);

    // Query update
    $queryUpdate = "UPDATE respon_task_skenario SET time_completion = ?, task_sukses = ?, number_errors = ?, catatan = ? WHERE id_survei = ? AND id_responden = ? AND id_task = ? AND id_respon_task = ?";
    $stmtUpdateTaskCatatan = $conn->prepare($queryUpdate);
    $stmtUpdateTaskCatatan->bind_param("iiisiiii", $waktuPenyelesaian, $sukses_gagal, $jumlahError, $catatanObservasi, $id_survei, $id_responden, $id_task_skenario, $id_respon_task);

    // cek apakah berhasil apa gagal
    if ($stmtUpdateTaskCatatan->execute()) {
        // Jika berhasil
        echo "<script>
            alert('Data task skenario responden berhasil diperbarui!');
            window.location.href='?page=detail-responden&id_responden=$id_responden&id_survei=$id_survei';
        </script>";
        exit;
    } else {
        # jika gagal
        echo "<script>alert('Gagal memperbarui survei.');
        window.location.href='?page=detail-responden&id_responden=$id_responden&id_survei=$id_survei';
        </script>";
    }
}

?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Survei Saya</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Detail Survei</a></li>
                    <li class="breadcrumb-item active">Edit Task Skenario Catatan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <form action="" method="post">
            <input type="hidden" name="id_survei" value="<?= $id_survei; ?>">
            <input type="hidden" name="id_responden" value="<?= $id_responden; ?>">
            <input type="hidden" name="id_respon_task" value="<?= $dataResponTask['id_respon_task']; ?>">

            <div class="row">
                <section class="col-md-12">
                    <!-- Card Task Skenario -->
                    <div class="card card-outline card-warning mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Task Skenario</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="my-3"><?= htmlspecialchars($data['task_skenario']); ?></h3>
                                            <?= htmlspecialchars_decode($data['description']); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="card card-outline card-warning shadow-lg">
                                        <div class="card-header">
                                            <h4 class="card-title">
                                                Keterangan
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Waktu Penyelesaian (satuan detik)</label>
                                                <input type="number" class="form-control" name="waktuPenyelesaian" placeholder="Waktu Penyelesaian satuan detik" value="<?= $dataResponTask['time_completion']; ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Jumlah Error</label>
                                                <input type="number" class="form-control" name="jumlahError" placeholder="Jumlah Error" value="<?= $dataResponTask['number_errors']; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label>Task Berhasil?</label>

                                                <div class="form-row mt-2">
                                                    <div class="col">
                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input" name="sukses-gagal" value="sukses" <?= ($dataResponTask['task_sukses'] === 1) ? 'checked' : ''; ?> required>
                                                            <label class="form-check-label">Sukses</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input type="radio" class="form-check-input" name="sukses-gagal" value="gagal" <?= ($dataResponTask['task_sukses'] === 0) ? 'checked' : ''; ?> required>
                                                            <label class="form-check-label">Gagal</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label>Catatan</label>
                                                <textarea class="summernoteTaskSkenarioCatatan" name="catatanObservasi" rows="5"><?= $dataResponTask['catatan']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="row py-3">
                <div class="col-12">
                    <a href="?page=list-responden&id_survei=<?= $id_survei; ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-save"></i> Submit</button>
                </div>
            </div>
        </form>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>

</script>