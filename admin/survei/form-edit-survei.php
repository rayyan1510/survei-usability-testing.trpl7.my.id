<?php

$id_survei =  isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;

if ($id_survei <= 0) {
    # cek jika id_survei tidak ada, maka redirect halaman
    echo "<script>alert('ID survei tidak valid!'); window.history.back();</script>";
    exit;
}

// Proses Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Simpan data dari parameter
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;

    $nama_survei = trim($_POST['namaSurvei']);
    $deskripsi = trim($_POST['deskripsi']);

    // cek jika kosong
    if ($nama_survei === '' || $deskripsi === '') {
        echo "<script>alert('Nama survei dan deskripsi tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }

    $queryUpdate = $conn->prepare("UPDATE surveys SET title = ?, description = ? WHERE id = ?");
    $queryUpdate->bind_param("ssi", $nama_survei, $deskripsi, $id_survei);

    // cek apakah berhasil update data
    if ($queryUpdate->execute()) {
        // Jika berhasil
        echo "<script>
            alert('Data survei berhasil diperbarui!');
            window.location.href='?page=detail-survei&id_survei=$id_survei';
        </script>";
        exit;
    } else {
        # jika gagal
        echo "<script>alert('Gagal memperbarui survei.');</script>";
    }
}

// Ambil data survei dan tampilkan
$query = $conn->prepare("SELECT * FROM surveys WHERE id = ?");
$query->bind_param("i", $id_survei);
$query->execute();
$result = $query->get_result();

// cek jika hasilnya kosong
if ($result->num_rows === 0) {
    // Redirect kembali dan tampilkan pesan
    echo "<script>alert('Data survei tidak ditemukan!'); window.history.back();</script>";
    exit;
}

// simpan data menjadi array
$data = $result->fetch_assoc();
$nama_survei = htmlspecialchars($data['title']);
$deskripsi = htmlspecialchars($data['description']);

// var_dump($data);


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
                    <li class="breadcrumb-item active">Edit Survei</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-12">
                <!-- card -->
                <div class="card card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            Edit Survei
                        </h3>

                    </div>
                    <form action="" method="post">
                        <input type="hidden" name="id_survei" value="<?= $id_survei ?>">

                        <div class="card-body">
                            <div class="form-group">
                                <label for="namaSurvei">Nama Survei</label>
                                <input type="text" class="form-control" name="namaSurvei" id="namaSurvei" value="<?= $nama_survei; ?>" placeholder="Silahkan Masukkan Judul" required>
                            </div>

                            <div class="form-group">
                                <label for="summernoteEditSurvei">Deskripsi Survei</label>
                                <textarea id="summernoteEditSurvei" name="deskripsi" required><?= htmlspecialchars_decode($deskripsi); ?></textarea>
                            </div>
                        </div>

                        <!-- /.card-body-->
                        <div class="card-footer">
                            <a href="#" class="btn btn-secondary" onclick="navigasibalik()"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-save"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->

            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    function navigasibalik() {
        window.history.back();
    }
</script>