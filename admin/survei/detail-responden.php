<?php


// var_dump($id_login);

$id_login = htmlspecialchars($_SESSION['id_login']);
$id_survei = htmlspecialchars($_GET['id_survei']);
$id_responden  = htmlspecialchars($_GET['id_responden']);

// ========================================================
// Query untuk info utama survei (satu baris)
$sqlInfoSurvei = "SELECT DISTINCT id_survei, title, survey_description, status, id_login, survey_created_at 
                  FROM v_survei_detail 
                  WHERE id_survei = ? AND id_login = ?";
$stmtInfo = $conn->prepare($sqlInfoSurvei);
$stmtInfo->bind_param("ii", $id_survei, $id_login);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();
$dataSurvei = $resultInfo->fetch_assoc();


// =========================================================
// Detail responden
$sqlDetailResponden = "SELECT * FROM biodata_responden WHERE id_responden = ? AND id_survei = ?";
$stmtDetailResponden  = $conn->prepare($sqlDetailResponden);
$stmtDetailResponden->bind_param("ii", $id_responden, $id_survei);
$stmtDetailResponden->execute();
$resultDetailResponden = $stmtDetailResponden->get_result();

if ($resultDetailResponden->num_rows === 0) {
    # cek jika detail responden tidak ada, maka redirect halaman
    echo "<script>alert('Tidak ditemukan responden!'); window.history.back();</script>";
    exit;
}

$dataDetailResponden =  $resultDetailResponden->fetch_assoc();

// ===================================================
// Function Format tanggal dalam Indo
function formatTanggalIndo($tanggalDb)
{
    $bulanIndo = [
        'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];
    $tanggal = new DateTime($tanggalDb);
    $hari = $tanggal->format('d');
    $bulan = $bulanIndo[(int)$tanggal->format('m') - 1];
    $tahun = $tanggal->format('Y');
    $jamMenit = $tanggal->format('H:i');
    return "$hari $bulan $tahun, $jamMenit WIB";
}

// =======================================
// detail task
$sqlDetailTaskResponden = "SELECT id_task_skenario, task_skenario, time_completion, task_sukses, number_errors, catatan FROM `v_respon_task_skenario` WHERE id_responden = ? AND id_survei = ?";
$stmtDetailTaskResponden  = $conn->prepare($sqlDetailTaskResponden);
$stmtDetailTaskResponden->bind_param("ii", $id_responden, $id_survei);
$stmtDetailTaskResponden->execute();
$resultDetailTaskResponden = $stmtDetailTaskResponden->get_result();


if ($resultDetailTaskResponden->num_rows === 0) {
    # cek jika detail responden tidak ada, maka redirect halaman
    echo "<script>alert('Tidak ditemukan responden!');</script>";
    exit;
}



// ==========================================
// detail survei sus jawaban detail respoden
$sqlDetailSUSResponden = "SELECT r.nama_responden AS nama_responden, p.id_sus, p.nomor_pernyataan, p.pernyataan, rs.answer, hs.total_skor_sus FROM respon_sus rs JOIN biodata_responden r ON rs.id_responden = r.id_responden JOIN survei_sus p ON rs.id_survei_sus = p.id_sus JOIN hasil_skor_sus hs ON rs.id_responden = hs.id_responden AND rs.id_survei = hs.id_survei WHERE rs.id_survei = ? AND rs.id_responden = ? ORDER BY p.nomor_pernyataan ASC";
$stmtDetailSUSResponden  = $conn->prepare($sqlDetailSUSResponden);
$stmtDetailSUSResponden->bind_param("ii", $id_survei, $id_responden);
$stmtDetailSUSResponden->execute();
$resultDetailSUSResponden = $stmtDetailSUSResponden->get_result();



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
                    <li class="breadcrumb-item">
                        <a href="?page=detail-survei&id_survei=<?= $id_survei; ?>"><?= htmlspecialchars($dataSurvei['title']) ?></a>
                    </li>
                    <li class="breadcrumb-item active"><a href="?page=list-responden&id_survei=<?= $id_survei; ?>">Tabel Responden</a></li>
                    <li class=" breadcrumb-item active">
                        <?= ucwords(htmlspecialchars($dataDetailResponden['nama_responden'])); ?>
                    </li>
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
            <section class="col-12">
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fas fa-poll"></i> <?= htmlspecialchars($dataSurvei['title']) ?>
                                <small class="float-right">Joining Date: <?= formatTanggalIndo($dataDetailResponden['created_at']); ?></small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row">
                        <div class="col-3">
                            <!-- Profile Image -->
                            <div class="card">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="../assets/vendor/admin-lte/dist/img/user2-160x160.jpg"
                                            alt="User profile picture">
                                    </div>

                                    <h3 class="profile-username text-center"><?= ucwords(htmlspecialchars($dataDetailResponden['nama_responden'])); ?></h3>


                                    <div class="card-header bg-primary">
                                        <h3 class="card-title">Tentang Responden</h3>
                                    </div>

                                    <div class="card-body">
                                        <strong><i class="fas fa-birthday-cake mr-1"></i> Umur</strong>

                                        <p class="text-muted">
                                            <?= ucwords(htmlspecialchars($dataDetailResponden['umur'])); ?> Tahun
                                        </p>

                                        <hr>

                                        <strong><i class="fas fa-briefcase mr-1"></i> Pekerjaan</strong>

                                        <p class="text-muted"><?= ucwords(htmlspecialchars($dataDetailResponden['pekerjaan'])); ?></p>

                                    </div>
                                    <!-- /.card-body -->

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                            <a href="?page=cetak-responden-detail&id_responden=<?= $id_responden; ?>&id_survei=<?= $id_survei; ?>" class="btn btn-info" target="_blank"><i class="fas fa-print"></i> Cetak Detail Responden</a>
                        </div>
                        <!-- /.col -->
                        <div class="col-9">

                            <div class="card">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#TaskSkenario" data-toggle="tab">Task Skenario</a></li>

                                        <li class="nav-item"><a class="nav-link" href="#sus" data-toggle="tab">System Usability Scale</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <!--  task skenario -->
                                        <div class="active tab-pane" id="TaskSkenario">
                                            <div class="table-responsive">
                                                <table id="survei1" class="table table-bordered table-striped" style="width: 100%;">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Task</th>
                                                            <th>Waktu Penyelesaian</th>
                                                            <th>Jumlah Error</th>
                                                            <th>Task Sukses</th>
                                                            <th>Catatan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $no = 1;
                                                        while ($dataDetailTaskResponden = $resultDetailTaskResponden->fetch_assoc()) { ?>

                                                            <tr>
                                                                <td><?= $no++; ?></td>
                                                                <td><?= htmlspecialchars($dataDetailTaskResponden['task_skenario']); ?></td>
                                                                <td><?= $dataDetailTaskResponden['time_completion']; ?>s</td>
                                                                <td><?= $dataDetailTaskResponden['number_errors']; ?></td>
                                                                <td><?= ($dataDetailTaskResponden['task_sukses'] === 1) ? 'Berhasil <span class="text-success">✓</span>' : 'Gagal <span class="text-danger">✗</span>'; ?></td>
                                                                <td><?= $dataDetailTaskResponden['catatan']; ?></td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <a href="?page=edit-tanggapi-responden&id_survei=<?= $id_survei; ?>&id_task=<?= $dataDetailTaskResponden['id_task_skenario']; ?>&id_responden=<?= $id_responden; ?>" class="btn btn-warning btn-sm mr-2"><i class="fas fa-edit"></i> Edit</a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->

                                        <!-- kuesioner sus -->
                                        <div class="tab-pane" id="sus">

                                            <?php

                                            if ($resultDetailSUSResponden->num_rows > 0) {
                                                $firstRow = $resultDetailSUSResponden->fetch_assoc();

                                                // Fungsi untuk menentukan kategori lengkap SUS Score
                                                function getKategoriSUSDetail($skor)
                                                {
                                                    // Grade sesuai yang kamu minta (tidak diubah)
                                                    if ($skor >= 79) {
                                                        $grade = 'A';
                                                    } elseif ($skor >= 72.5) {
                                                        $grade = 'B';
                                                    } elseif ($skor >= 62.5) {
                                                        $grade = 'C';
                                                    } elseif ($skor >= 52.5) {
                                                        $grade = 'D';
                                                    } else {
                                                        $grade = 'F';
                                                    }

                                                    // Tambahan berdasarkan gambar skala SUS
                                                    // Acceptability
                                                    if ($skor >= 71.4) {
                                                        $acceptability = 'Acceptable';
                                                    } elseif ($skor >= 50.9) {
                                                        $acceptability = 'Marginal';
                                                    } else {
                                                        $acceptability = 'Not Acceptable';
                                                    }

                                                    // Adjective Rating
                                                    if ($skor >= 90) {
                                                        $adjective = 'Best Imaginable';
                                                    } elseif ($skor >= 85) {
                                                        $adjective = 'Excellent';
                                                    } elseif ($skor >= 71) {
                                                        $adjective = 'Good';
                                                    } elseif ($skor >= 51) {
                                                        $adjective = 'OK';
                                                    } elseif ($skor >= 25) {
                                                        $adjective = 'Poor';
                                                    } else {
                                                        $adjective = 'Worst Imaginable';
                                                    }

                                                    // NPS Label
                                                    if ($skor >= 80.3) {
                                                        $nps = 'Promoter';
                                                    } elseif ($skor >= 52) {
                                                        $nps = 'Passive';
                                                    } else {
                                                        $nps = 'Detractor';
                                                    }

                                                    return [
                                                        'score' => $skor,
                                                        'grade' => $grade,
                                                        'acceptability' => $acceptability,
                                                        'adjective' => $adjective,
                                                        'nps' => $nps
                                                    ];
                                                }

                                                $grade = getKategoriSUSDetail($firstRow['total_skor_sus']);



                                                echo "<h6>Total Skor SUS: <strong>" . $firstRow['total_skor_sus'] . " " . $grade['grade'] . ' ' . $grade['acceptability'] . "</strong></h6><hr>";

                                                // reset pointer sebelum loop ulang
                                                $resultDetailSUSResponden->data_seek(0);

                                            ?>

                                                <!-- Petunjuk -->
                                                <div class="alert alert-info">
                                                    <strong>Petunjuk:</strong> Pernyataan di bawah ini merupakan bagian dari kuesioner SUS (Aspek Satisfaction).<br>
                                                    Setiap pernyataan memiliki skala dari <strong>1 (Sangat Tidak Setuju)</strong> hingga <strong>5 (Sangat Setuju)</strong>.
                                                </div>

                                                <?php
                                                while ($dataDetailSUSResponden = $resultDetailSUSResponden->fetch_assoc()) {
                                                    $no_urut = $dataDetailSUSResponden['nomor_pernyataan'];
                                                    $pernyataan = $dataDetailSUSResponden['pernyataan'];
                                                    $jawaban = $dataDetailSUSResponden['answer'];
                                                    $id_pernyataan = $dataDetailSUSResponden['id_sus'];
                                                ?>
                                                    <div class="form-group px-3 py-3">
                                                        <label class="mb-2 font-weight-bold"><?= $no_urut; ?>. <?= htmlspecialchars($pernyataan); ?></label>

                                                        <div class="d-flex flex-wrap">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <div class="custom-control custom-radio mr-5 pr-0">
                                                                    <input class="custom-control-input" type="radio" name="a_<?= $id_pernyataan; ?>" id="a_<?= $id_pernyataan; ?>_<?= $i; ?>" value="<?= $i; ?>" <?= ($jawaban === $i) ? 'checked' : ''; ?> disabled>
                                                                    <label class="custom-control-label">
                                                                        <?= $i ?>
                                                                    </label>
                                                                </div>
                                                            <?php endfor; ?>
                                                        </div>

                                                    </div>
                                                <?php
                                                }
                                            } else {
                                                ?>

                                                <!-- Petunjuk -->
                                                <div class="alert alert-warning">
                                                    <strong>Petunjuk:</strong> Responden Belum Mengisi Kuesioner SUS! (Aspek Satisfaction)
                                                </div>

                                            <?php } ?>


                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->

                        </div>

                    </div>
                    <!-- /.row -->


                </div>
            </section>

        </div>


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->