<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei Usability Testing | 404 Page not found</title>

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
    <!-- JQVMap -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/jqvmap/jqvmap.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/bs-stepper/css/bs-stepper.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/dist/css/adminlte.min.css">

    <!-- summernote -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.css">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .error-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            flex-direction: column;
            padding: 20px;
        }

        .error-content h3 {
            margin-top: 20px;
            font-weight: 600;
        }

        .error-content p {
            font-size: 1rem;
            margin-top: 10px;
            color: #555;
        }

        .nyan-cat {
            margin: 20px 0;
            max-width: 280px;
            width: 100%;
        }

        .headline {
            font-size: 5rem;
            font-weight: 700;
        }

        @media (max-width: 576px) {
            .headline {
                font-size: 3rem;
            }

            .nyan-cat {
                max-width: 200px;
            }
        }
    </style>


</head>


<body class="hold-transition">

    <div class="error-wrapper">
        <h2 class="headline text-warning">404</h2>

        <div class="error-content">
            <!-- GIF Kucing Nyan Cat -->
            <img src="./assets/img/giphy.gif" alt="Nyan Cat" class="nyan-cat">

            <h3><i class="fas fa-exclamation-triangle text-warning"></i> Oops! Halaman tidak ditemukan.</h3>
            <p>
                Kami tidak dapat menemukan halaman yang kamu cari.<br>
                Silakan <a href="./index.php">kembali ke beranda</a> atau periksa kembali URL-nya.
            </p>
        </div>
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

    <!-- DataTables  & Plugins -->
    <script src="./assets/vendor/admin-lte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/jszip/jszip.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- BS-Stepper -->
    <script src="./assets/vendor/admin-lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- ChartJS -->
    <script src="./assets/vendor/admin-lte/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="./assets/vendor/admin-lte/plugins/sparklines/sparkline.js"></script>
    <!-- JQVMap -->
    <script src="./assets/vendor/admin-lte/plugins/jqvmap/jquery.vmap.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="./assets/vendor/admin-lte/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="./assets/vendor/admin-lte/plugins/moment/moment.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="./assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- Summernote -->
    <script src="./assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="./assets/vendor/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="./assets/vendor/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- AdminLTE App -->
    <script src="./assets/vendor/admin-lte/dist/js/adminlte.js"></script>

    <!-- Summernote -->
    <script src="./assets/vendor/admin-lte/plugins/summernote/summernote-bs4.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <!-- <script src="./assets/vendor/admin-lte/dist/js/demo.js"></script> -->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="./assets/vendor/admin-lte/dist/js/pages/dashboard.js"></script>
    <!-- My Js -->
</body>

</html>