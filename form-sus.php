<?php

include './database/database.php';

// var_dump($_GET);

$id_survei =  isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;
$id_responden =  isset($_GET['id_responden']) ? (int) $_GET['id_responden'] : 0;

if ($id_survei <= 0 || $id_responden <= 0) {
    # cek jika id_survei tidak ada, maka redirect halaman
    echo "<script>alert('Survei tidak ditemukan!'); window.location.href='./404.php';</script>";
    exit;
}

// Cek status responden
$sqlResponden = "SELECT konfirmasi FROM biodata_responden WHERE id_responden = ? AND id_survei = ?";
$stmtResponden = $conn->prepare($sqlResponden);
$stmtResponden->bind_param("ii", $id_responden, $id_survei);
$stmtResponden->execute();
$resultResponden = $stmtResponden->get_result();


if ($resultResponden->num_rows === 0) {
    echo "<script>alert('Data responden tidak ditemukan!'); window.location.href='./404.php';</script>";
    exit;
}

// $dataResponden = $resultResponden->fetch_assoc();

// // var_dump($dataResponden['konfirmasi']);

// if ($dataResponden['konfirmasi'] !== 1) {
//     echo "<script>alert('Maaf, kamu belum dikonfirmasi oleh praktisi.'); window.location.href='./404.php';</script>";
//     exit;
// }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei Usability Testing</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/fontawesome-free/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">



    <!-- Theme style -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/dist/css/adminlte.min.css">


    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

</head>

<?php
// Query untuk ambil survei
$sqlSurvei = "SELECT * FROM surveys WHERE id = ?";
$stmtSurvei = $conn->prepare($sqlSurvei);
$stmtSurvei->bind_param("i", $id_survei);
$stmtSurvei->execute();
$resultDetail = $stmtSurvei->get_result();

// simpan data menjadi array
$data = $resultDetail->fetch_assoc();

// Query untuk survei sus
$sqlSurveiSus = "SELECT * FROM survei_sus WHERE id_survei = ?";
$stmtSurveiSus = $conn->prepare($sqlSurveiSus);
$stmtSurveiSus->bind_param("i", $id_survei);
$stmtSurveiSus->execute();
$resultSurveiSus = $stmtSurveiSus->get_result();


// simpan data menjadi array
// $data = $resultDetail->fetch_assoc();
?>

<body class="hold-transition layout-top-nav">

    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper pt-3">
            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    // cek apakah published
                                    if ($data['status'] == 'published') :
                                    ?>
                                        <h4 class="text-center">Aspek Satisfaction - System Usability Scale</h4>

                                        <!-- Petunjuk -->
                                        <div class="alert alert-warning">
                                            <strong>Petunjuk:</strong> Pernyataan di bawah ini merupakan bagian dari kuesioner SUS.<br>
                                            Setiap pernyataan memiliki skala dari <strong>1 (Sangat Tidak Setuju)</strong> hingga <strong>5 (Sangat Setuju)</strong>.
                                        </div>

                                        <form action="./proses-simpan-sus.php" method="post">
                                            <input type="hidden" name="id_survei" value="<?= htmlspecialchars($id_survei); ?>">
                                            <input type="hidden" name="id_responden" value="<?= htmlspecialchars($id_responden); ?>">

                                            <?php foreach ($resultSurveiSus as $statementSus): ?>
                                                <input type="hidden" name="id_sus[]" value="<?= $statementSus['id_sus']; ?>">
                                                <div class="form-group px-3 py-3">
                                                    <label class="mb-2 font-weight-bold"><?= $statementSus['nomor_pernyataan']; ?>. <?= htmlspecialchars($statementSus['pernyataan']); ?></label>
                                                    <div class="d-flex flex-wrap">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <div class="form-check mr-5 pr-0">
                                                                <input class="form-check-input" type="radio" name="q<?= $statementSus['id_sus']; ?>" id="q<?= $statementSus['id_sus']; ?>_<?= $i; ?>" value="<?= $i; ?>" required>
                                                                <label class="form-check-label" for="q<?= $statementSus['nomor_pernyataan']; ?>_<?= $i; ?>">
                                                                    <?= $i ?>
                                                                </label>
                                                            </div>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>

                                            <div class="text-right mt-4">
                                                <button type="submit" name="submit" class="btn btn-success">
                                                    <i class="fas fa-save"></i> Konfirmasi
                                                </button>
                                            </div>
                                        </form>
                                    <?php else: ?>
                                        <?php

                                        echo "<script>alert('Survei tidak ditemukan!'); window.location.href='./404.php';</script>";
                                        exit;

                                        ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
    </div>


    <!-- jQuery -->
    <script src="./assets/vendor/admin-lte/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="./assets/vendor/admin-lte/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="./assets/vendor/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="./assets/vendor/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- ChartJS -->
    <script src="./assets/vendor/admin-lte/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="./assets/vendor/admin-lte/plugins/sparklines/sparkline.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="./assets/vendor/admin-lte/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="./assets/vendor/admin-lte/plugins/moment/moment.min.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="./assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="./assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="./assets/vendor/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./assets/vendor/admin-lte/dist/js/adminlte.js"></script>

    <!-- AdminLTE for demo purposes -->
    <!-- <script src="./assets/vendor/admin-lte/dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->

    <!-- My Js -->
</body>

</html>