<?php


$id_login = htmlspecialchars($_SESSION['id_login']);
$id_responden = htmlspecialchars($_GET['id_responden']);
$id_survei = htmlspecialchars($_GET['id_survei']);

// Query untuk ambil data task skenario
$sqlTaskSkenario = "SELECT * FROM surveys_task_skenario WHERE id_survei = ?";
$stmtTaskSkenario = $conn->prepare($sqlTaskSkenario);
$stmtTaskSkenario->bind_param("i", $id_survei);
$stmtTaskSkenario->execute();
$resultTaskSkenario = $stmtTaskSkenario->get_result();

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
                    <li class="breadcrumb-item active">Task Skenario Catatan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <form action="?page=proses-simpan-task-catatan" method="post">
            <input type="hidden" name="id_survei" value="<?= $id_survei; ?>">
            <input type="hidden" name="id_responden" value="<?= $id_responden; ?>">

            <div class="row">
                <?php
                $no = 1;
                while ($taskSkenario = $resultTaskSkenario->fetch_assoc()) :
                ?>
                    <section class="col-md-12">
                        <!-- Card Task Skenario -->
                        <div class="card card-outline card-warning collapsed-card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Task Skenario <?= $no; ?></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row m-0">
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="my-3"><?= htmlspecialchars($taskSkenario['task_skenario']); ?></h3>
                                                <?= htmlspecialchars_decode($taskSkenario['description']); ?>
                                                <input type="hidden" name="id_task_skenario[]" value="<?= $taskSkenario['id']; ?>">
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
                                                    <label>Waktu Penyelesaian</label>
                                                    <input type="number" class="form-control" name="waktuPenyelesaian[]" placeholder="Waktu Penyelesaian satuan detik" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah Error</label>
                                                    <input type="number" class="form-control" name="jumlahError[]" placeholder="Jumlah Error">
                                                </div>

                                                <div class="form-group">
                                                    <label>Task Berhasil?</label>

                                                    <div class="form-row">
                                                        <div class="col">
                                                            <div class="form-check form-check-inline">
                                                                <input type="radio" class="form-check-input" name="sukses-gagal[<?= $no ?>]" value="sukses" required>
                                                                <label class="form-check-label">Sukses</label>
                                                            </div>

                                                            <div class="form-check form-check-inline">
                                                                <input type="radio" class="form-check-input" name="sukses-gagal[<?= $no ?>]" value="gagal" required>
                                                                <label class="form-check-label">Gagal</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Catatan</label>
                                                    <textarea class="summernoteTaskSkenarioCatatan" name="catatanObservasi[]" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                <?php
                    $no++;
                endwhile;
                ?>
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