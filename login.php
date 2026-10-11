<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Survei Usability Testing | Login</title>

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
</head>

<?php
session_start();
if (isset($_SESSION['alert'])) {
    $alert = $_SESSION['alert'];
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '{$alert['type']}',
                title: '{$alert['title']}',
                text: '{$alert['message']}'
            });
        });
    </script>";
    unset($_SESSION['alert']);
}
?>

<body class="hold-transition login-page">

    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Survei</b> UBT</a>
        </div>

        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in untuk membuat survei</p>

                <form action="./proses-login.php" method="post">

                    <div class="input-group mb-3">
                        <input type="email" class="form-control" name="email" placeholder="Email">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <!-- <div class="icheck-primary">
                                <input type="checkbox" id="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div> -->
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mb-0">
                    <a href="./register.php" class="text-center">Belum punya akun?</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->


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
    <!-- SweetAlert2 -->
    <script src="./assets/vendor/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>
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