<?php

// Ambil id_survei
$id_survei = isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;
$id_task = isset($_GET['id_task']) ? (int) $_GET['id_task'] : 0;

$id_login = htmlspecialchars($_SESSION['id_login']);

if ($id_survei <= 0 && $id_task <= 0) {
    # cek jika id_survei dan id_task tidak ada, maka redirect halaman
    echo "<script>alert('ID survei dan ID Task Skenario tidak valid!'); window.history.back();</script>";
    exit;
}

// Query untuk info utama survei (satu baris)
$sqlInfoSurvei = "SELECT DISTINCT id_survei, title, survey_description, status, id_login, survey_created_at 
                  FROM v_survei_detail 
                  WHERE id_survei = ? AND id_login = ?";
$stmtInfo = $conn->prepare($sqlInfoSurvei);
$stmtInfo->bind_param("ii", $id_survei, $id_login);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();
$dataSurvei = $resultInfo->fetch_assoc();


// Proses Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirim
    $id_survei = $_POST['id_survei'];

    $id_task = $_POST['id_task'];
    $judul = trim($_POST['taskSkenario']);
    $deskripsi = trim($_POST['deskripsiTeksSkenario']);

    // cek jika kosong
    if ($judul === '' || $deskripsi === '') {
        echo "<script>alert('Nama Task Skenario dan deskripsi tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }

    // Update  task yang sudah ada
    $stmt = $conn->prepare("UPDATE surveys_task_skenario SET task_skenario = ?, description = ? WHERE id = ? AND id_survei = ?");
    $stmt->bind_param("ssii", $judul, $deskripsi, $id_task, $id_survei);


    if ($stmt->execute()) {
        echo "<script>alert('Task skenario berhasil diperbarui!');window.location.href='?page=detail-survei&id_survei=$id_survei';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data!'); window.history.back();</script>";
    }
    exit;
}

// ==================================================================
// Ambil data survei dan tampilkan id, task_skenario, description
$query = $conn->prepare("SELECT * FROM surveys_task_skenario WHERE id = ? AND id_survei = ?");
$query->bind_param("ii", $id_task, $id_survei);
$query->execute();
$result = $query->get_result();

// cek jika hasilnya kosong
if ($result->num_rows === 0) {
    // Redirect kembali dan tampilkan pesan
    echo "<script>alert('Data task skenario tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$data = $result->fetch_assoc();

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
                    <li class="breadcrumb-item"> <a href="?page=detail-survei&id_survei=<?= $id_survei; ?>"><?= htmlspecialchars($dataSurvei['title']) ?></a></li>
                    <li class="breadcrumb-item "><a href="#">Task Skenario</a></li>
                    <li class="breadcrumb-item active">Edit Task Skenario</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <div class="row">
            <!-- Kolom kiri -->
            <section class="col-md-8">
                <!-- Card  -->
                <div class="card card-outline card-warning task-card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Task Skenario</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>

                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <input type="hidden" name="id_task" value="<?= $data['id'] ?>">
                            <input type="hidden" name="id_survei" value="<?= $_GET['id_survei']; ?>">
                            <!-- Task Skenario -->
                            <div class="form-group">
                                <label for="taskSkenario">Task Skenario</label>
                                <input type="text" class="form-control" id="taskSkenario" name="taskSkenario" placeholder="Silahkan Masukkan Judul" value="<?= $data['task_skenario']; ?>" required>
                            </div>
                            <!-- Deskripsi Teks Skenario -->
                            <div class="form-group">
                                <label>Deskripsi Teks Skenario</label>
                                <textarea class="summernoteTaskSkenario" name="deskripsiTeksSkenario" rows="5" required><?= htmlspecialchars_decode($data['description']); ?></textarea>
                            </div>
                    </div>
                </div>
            </section>

            <!-- Kolom kanan -->
            <section class="col-md-4">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Pengaturan</h3>
                    </div>
                    <div class="card-body">
                        <a href="#" class="btn btn-secondary" onclick="navigasibalik()"><i class="fas fa-arrow-left"></i> Kembali</a>
                        <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-save"></i> Submit</button>
                    </div>
                </div>
            </section>
        </div>
        </form>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    function navigasibalik() {
        window.history.back();
    }
</script>