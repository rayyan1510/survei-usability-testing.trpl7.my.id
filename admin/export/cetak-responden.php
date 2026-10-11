<?php


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

// if ($resultDetailResponden->num_rows === 0) {
//     # cek jika detail responden tidak ada, maka redirect halaman
//     echo "<script>alert('Tidak ditemukan responden!'); window.history.back();</script>";
//     exit;
// }

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


// if ($resultDetailTaskResponden->num_rows === 0) {
//     # cek jika detail responden tidak ada, maka redirect halaman
//     echo "<script>alert('Tidak ditemukan responden!');</script>";
//     exit;
// }



// ==========================================
// detail survei sus jawaban detail respoden
$sqlDetailSUSResponden = "SELECT r.nama_responden AS nama_responden, p.id_sus, p.nomor_pernyataan, p.pernyataan, rs.answer, hs.total_skor_sus FROM respon_sus rs JOIN biodata_responden r ON rs.id_responden = r.id_responden JOIN survei_sus p ON rs.id_survei_sus = p.id_sus JOIN hasil_skor_sus hs ON rs.id_responden = hs.id_responden AND rs.id_survei = hs.id_survei WHERE rs.id_survei = ? AND rs.id_responden = ? ORDER BY p.nomor_pernyataan ASC";
$stmtDetailSUSResponden  = $conn->prepare($sqlDetailSUSResponden);
$stmtDetailSUSResponden->bind_param("ii", $id_survei, $id_responden);
$stmtDetailSUSResponden->execute();
$resultDetailSUSResponden = $stmtDetailSUSResponden->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei Usability Testing | Dashboard</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/jqvmap/jqvmap.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/datatables-fixedheader/css/fixedHeader.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/datatables-scroller/css/scroller.bootstrap4.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/bs-stepper/css/bs-stepper.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/dist/css/adminlte.min.css">

    <!-- summernote -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="../assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.css">

    <style>
        @media print {
            @page {
                /* margin: 0mm;
                size: auto; */

                /* Hanya nomor halaman, hilangkan header/footer default (seperti URL) */
                @bottom-left {
                    content: counter(page) " / " counter(pages);
                }
            }

            /* Sembunyikan semua elemen yang tidak perlu */
            .no-print,
            footer,
            .footer,
            header,
            nav {
                display: none !important;
            }
        }
    </style>



</head>

<body>

    <div class="wrapper">
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
                            <div class="row invoice-info">
                                <div class="col-12 mb-3">
                                    <!-- Profile Image -->
                                    <div class="container">
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


                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->


                            <div class="row">
                                <div class="col-12 table-responsive">

                                    <table id="" class="table table-bordered table-striped" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Task</th>
                                                <th>Waktu Penyelesaian</th>
                                                <th>Jumlah Error</th>
                                                <th>Task Sukses</th>
                                                <th>Catatan</th>
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

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <hr><br><br><br><br>

                            <div class="row my-5 py-5">
                                <div class="col-12">

                                    <h1 class="py-4">Skor System Usability Scale "Satisfaction"</h1>

                                    <?php

                                    if ($resultDetailSUSResponden->num_rows > 0) {
                                        $firstRow = $resultDetailSUSResponden->fetch_assoc();
                                        // Fungsi untuk menentukan kategori SUS
                                        function getKategoriSUS($skor)
                                        {
                                            if ($skor >= 79) {
                                                return 'A (Excellent)';
                                            } elseif ($skor >= 72.5) {
                                                return 'B (Good)';
                                            } elseif ($skor >= 62.5) {
                                                return 'C (OK/Fair)';
                                            } elseif ($skor >= 52.5) {
                                                return 'D (Poor)';
                                            } else {
                                                return 'F (Awful)';
                                            }
                                        }


                                        echo "<h5>Total Skor SUS: <strong>" . $firstRow['total_skor_sus'] . " " . getKategoriSUS($firstRow['total_skor_sus']) . "</strong></h5>";

                                        // reset pointer sebelum loop ulang
                                        $resultDetailSUSResponden->data_seek(0);
                                    }

                                    ?>


                                    <!-- Petunjuk -->
                                    <div class="py-4">
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
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </section>

                </div>


            </div><!-- /.container-fluid -->
        </section>
    </div>

    <script>
        window.addEventListener("load", window.print());
    </script>

</body>

</html>