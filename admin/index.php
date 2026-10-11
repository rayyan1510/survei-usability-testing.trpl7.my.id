<?php
session_start();

// Include database
include '../database/database.php';

// cek apakah ada sesi login?
if (!isset($_SESSION['login'])) {
    // header("Location: ../login.php");
    echo "<script>window.location.href = ../login.php</script>";
    exit;
}


$id_login = $_SESSION['id_login'];

$sql = "SELECT * FROM v_akun_users WHERE id_login = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_login);
$stmt->execute();
$result = $stmt->get_result();

$userData = $result->fetch_assoc();
$stmt->close();


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

    <!-- Tambahkan CSS untuk tombol -->
    <style>
        .dt-buttons.btn-group {
            margin-bottom: 15px;
            margin-right: 5px;
        }

        .dataTables_wrapper .dataTables_info {
            clear: none;
            float: left;
            padding-top: 0.755em;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
        }

        a.dropdown-toggle::after {
            display: none !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- wrapper -->
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> -->

        <!-- Navbar -->
        <?php include './partials/navbar.php' ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include './partials/sidebar.php' ?>
        <!-- ./Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <?php include 'config/config.php'; ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        <?php include './partials/footer.php' ?>
        <!-- ./footer -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->


    <!-- jQuery -->
    <script src="../assets/vendor/admin-lte/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="../assets/vendor/admin-lte/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="../assets/vendor/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables  & Plugins -->
    <script src="../assets/vendor/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>



    <script src="../assets/vendor/admin-lte/plugins/jszip/jszip.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

    <script src="../assets/vendor/admin-lte/plugins/datatables-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-fixedheader/js/fixedHeader.bootstrap4.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/datatables-scroller/js/scroller.bootstrap4.min.js"></script>

    <!-- Bs Stapper -->
    <script src="../assets/vendor/admin-lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- Sweeetalert -->
    <script src="../assets/vendor/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>

    <!-- ChartJS -->
    <script src="../assets/vendor/admin-lte/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="../assets/vendor/admin-lte/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="../assets/vendor/admin-lte/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="../assets/vendor/admin-lte/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="../assets/vendor/admin-lte/plugins/moment/moment.min.js"></script>
    <script src="../assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="../assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="../assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="../assets/vendor/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/vendor/admin-lte/dist/js/adminlte.js"></script>

    <!-- Summernote -->
    <!-- <script src="../assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.js"></script> -->
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="../assets/vendor/admin-lte/dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../assets/vendor/admin-lte/dist/js/pages/dashboard.js"></script>
    <!-- My Js -->
    <script src="../assets/js/admin-js.js"></script>
    <script src="../assets/js/dashboard-admin.js"></script>


</body>

</html>