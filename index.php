<?php

include './database/database.php';


// ambil id survei dari paramter GET
$id_survei =  isset($_GET['id']) ? (int) $_GET['id'] : 0;
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
    <!-- SweetAlert2 -->
    <script src="./assets/vendor/admin-lte/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">

    <!-- BS Stepper -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/bs-stepper/css/bs-stepper.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/dist/css/adminlte.min.css">

    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="./assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.css">

    <style>
        #waiting-button .spinner-border {
            vertical-align: middle;
        }

        body {
            scroll-behavior: smooth;
        }

        header {
            background-color: #0d6efd;
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        section {
            padding: 60px 20px;
        }

        .navbar-brand {
            font-weight: bold;
        }

        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>

</head>

<body class="hold-transition layout-top-nav">

    <?php

    if ($id_survei > 0) {

        // Query untuk ambil survei
        $sqlSurvei = "SELECT * FROM surveys WHERE id = ?";
        $stmtSurvei = $conn->prepare($sqlSurvei);
        $stmtSurvei->bind_param("i", $id_survei);
        $stmtSurvei->execute();
        $resultDetail = $stmtSurvei->get_result();

        // cek jika survei tidak ditemukan  dan id_survei tidak ada, maka redirect halaman
        if ($resultDetail->num_rows === 0) {
            echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Warning!!!',
                text: 'Data survei tidak tersedia.',
                confirmButtonText: 'Oke',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = './404.php';
            });
        });
    </script>";
            exit;
        }

        // simpan data menjadi array
        $data = $resultDetail->fetch_assoc();

        // Query untuk ambil task
        $sqlTaskSkenario = "SELECT * FROM surveys_task_skenario WHERE id_survei = ?";
        $stmtTaskSkenario = $conn->prepare($sqlTaskSkenario);
        $stmtTaskSkenario->bind_param("i", $id_survei);
        $stmtTaskSkenario->execute();
        $resultDetailTask = $stmtTaskSkenario->get_result();

        // cek apakah published
        if ($data['status'] == 'published') {
    ?>
            <div class="wrapper">
                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper pt-3">

                    <!-- Main content -->
                    <div class="content">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-md-12">
                                    <div class="card py-3 px-4">
                                        <div class="card-body">


                                            <h4 class="text-center"><?= $data['title']; ?></h4>

                                            <p class="card-text mt-5">
                                                <?= $data['description']; ?>
                                            </p>

                                            <!-- Form -->
                                            <form method="post" class="mt-4" id="formResponden">

                                                <input type="hidden" name="id_survei" value="<?= $data['id']; ?>">

                                                <!-- Nama -->
                                                <div class="form-group">
                                                    <label for="nama">Nama</label>
                                                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama Anda" autocomplete="off" required>
                                                </div>

                                                <!-- Pekerjaan -->
                                                <div class="form-group">
                                                    <label>Pekerjaan</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="pekerjaan" id="pekerjaan1" value="Pelajar/Mahasiswa" autocomplete="off" required>
                                                        <label class=" form-check-label" for="pekerjaan1">Pelajar/Mahasiswa</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="pekerjaan" id="pekerjaan2" value="Pegawai Swasta" autocomplete="off" required>
                                                        <label class=" form-check-label" for="pekerjaan2">Pegawai Swasta</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="pekerjaan" id="pekerjaan3" value="Pegawai Negeri" autocomplete="off" required>
                                                        <label class="form-check-label" for="pekerjaan3">Pegawai Negeri</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="pekerjaan" id="pekerjaan4" value="Lainnya" autocomplete="off" required>
                                                        <label class="form-check-label" for="pekerjaan4">Lainnya</label>
                                                    </div>
                                                </div>

                                                <!-- Umur -->
                                                <div class="form-group">
                                                    <label>Umur</label>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur1" value="<18" required>
                                                        <label class="form-check-label" for="umur1">Kurang dari 18 tahun</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur2" value="18-25" autocomplete="off" required>
                                                        <label class="form-check-label" for="umur2">18 - 25 tahun</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur3" value="26-35" autocomplete="off" required>
                                                        <label class="form-check-label" for="umur3">26 - 35 tahun</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur4" value="36-45" autocomplete="off" required>
                                                        <label class="form-check-label" for="umur4">36 - 45 tahun</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur5" value="46-55" autocomplete="off" required>
                                                        <label class="form-check-label" for="umur5">46 - 55 tahun</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="umur" id="umur6" value=">55" autocomplete="off" required>
                                                        <label class="form-check-label" for="umur6">Lebih dari 55 tahun</label>
                                                    </div>
                                                </div>


                                                <!-- Accordion Task  -->
                                                <div id="accordion">
                                                    <?php
                                                    $index = 1;
                                                    while ($task = $resultDetailTask->fetch_assoc()) :
                                                        $collapseId = "task" . $index;
                                                    ?>
                                                        <div class="card card-info">
                                                            <div class="card-header">
                                                                <h4 class="card-title w-100">
                                                                    <a class="d-block <?= $index !== 1 ? 'collapsed' : '' ?>" data-toggle="collapse" href="#<?= $collapseId ?>">
                                                                        Task <?= $index ?>: <?= htmlspecialchars($task['task_skenario']); ?>
                                                                    </a>
                                                                </h4>
                                                            </div>
                                                            <div id="<?= $collapseId ?>" class="collapse <?= $index === 1 ? 'show' : '' ?>" data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <!-- <?= nl2br(htmlspecialchars($task['description'])); ?> -->
                                                                    <?= $task['description']; ?>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    <?php
                                                        $index++;
                                                    endwhile;
                                                    ?>
                                                </div>

                                                <!-- Persetujuan dan tombol konfirmasi di kanan -->
                                                <div class="card-footer">
                                                    <div class="d-flex flex-column align-items-end">
                                                        <div class="form-check mb-2" id="checkbox-container" style="width: fit-content;">
                                                            <input class="form-check-input" type="checkbox" id="setuju" name="setuju" required>
                                                            <label class="form-check-label" for="setuju">
                                                                Saya setuju untuk mengikuti survei ini
                                                            </label>
                                                        </div>
                                                        <div style="padding-left: 1.5rem;">
                                                            <button type="submit" name="submit" id="btnSubmit" class="btn btn-success"><i class="fas fa-save"></i> Submit</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- ./Form -->

                                            <!-- Tombol status konfirmasi (awalnya disembunyikan) -->
                                            <button id="waiting-button" class="btn btn-warning mt-3" disabled style="display: none;">
                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                Data dirimu sudah disubmit, silakan tunggu dan ikuti arahan praktisi 😊
                                            </button>

                                            <!-- Tombol selanjutnya (juga disembunyikan awalnya) -->
                                            <a id="next-button" href="#" class="btn btn-success mt-3" style="display: none;">Lanjutkan ke tahap terakhir</a>


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
        <?php
        } else {
            // jika survei statusnya selain published
            echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Warning!!!',
                        text: 'Survei ini tidak ditemukan atau mungkin telah dihapus.',
                        confirmButtonText: 'Oke',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        window.location.href = './404.php';
                    });
                });
            </script>";
            exit;
        } ?>

    <?php } else {
    ?>

        <div class="wrapper">
            <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
                <div class="container">
                    <a href="#" class="navbar-brand">
                        <span class="brand-text font-weight-light">Survei Usability Testing</span>
                    </a>
                    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="#home">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#about">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-light" href="login.php">Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content-wrapper">
                <div class="content">
                    <div class="container">
                        <section id="home">
                            <div class="text-center">
                                <h1 class="display-4">Selamat Datang</h1>
                                <p class="lead">di Sebuah Prototype Projek Sistem Survei Usability Testing</p>
                            </div>
                        </section>

                        <section id="about">
                            <div class="text-center">
                                <h2>Tentang Survei</h2>
                                <p>Ini adalah sistem survei sederhana yang digunakan untuk mengumpulkan pengalaman pengguna melalui metode usability testing. Pengguna dapat mengikuti survei dan memberikan masukan tentang antarmuka dan fitur yang diuji.</p>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <footer class="main-footer text-center">
                <strong>&copy; <?= date('Y') ?> Survei Usability Testing.</strong> All rights reserved.
            </footer>
        </div>
    <?php } ?>

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

    <!-- BS-Stepper -->
    <script src="./assets/vendor/admin-lte/plugins/bs-stepper/js/bs-stepper.min.js"></script>
    <!-- ChartJS -->
    <script src="./assets/vendor/admin-lte/plugins/chart.js/Chart.min.js"></script>
    <!-- Sparkline -->
    <script src="./assets/vendor/admin-lte/plugins/sparklines/sparkline.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="./assets/vendor/admin-lte/plugins/jquery-knob/jquery.knob.min.js"></script>
    <!-- daterangepicker -->
    <script src="./assets/vendor/admin-lte/plugins/moment/moment.min.js"></script>
    <script src="./assets/vendor/admin-lte/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="./assets/vendor/admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <!-- overlayScrollbars -->
    <script src="./assets/vendor/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>

    <!-- AdminLTE App -->
    <script src="./assets/vendor/admin-lte/dist/js/adminlte.js"></script>

    <!-- AdminLTE for demo purposes -->
    <!-- <script src="./assets/vendor/admin-lte/dist/js/demo.js"></script> -->
    <!-- My Js -->
    <script>
        document.getElementById("formResponden").addEventListener("submit", function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            // Sembunyikan checkbox dan tombol submit
            document.getElementById("checkbox-container").style.display = "none";
            document.getElementById("btnSubmit").style.display = "none";

            // Tampilkan tombol 'Mohon tunggu'
            const waitingBtn = document.getElementById("waiting-button");
            waitingBtn.style.display = "inline-block";

            // .then(res => res.json())

            // fetch("proses-simpan-responden.php", {
            //         method: "POST",
            //         body: formData
            //     })
            //     .then(res => res.text())
            //     .then(text => {
            //         console.log("Raw response:", text);
            //         const dataTest = JSON.parse(text);
            //     })
            // // .then(data => {


            //     // if (data.status === "success") {
            //     //     const id_survei = data.id_survei;
            //     //     const id_responden = data.id_responden;

            //     //     // Simpan ID untuk digunakan saat cek konfirmasi
            //     //     window.respondenID = id_responden;
            //     //     window.surveiID = id_survei;

            //     //     // Tampilkan link Selanjutnya sebagai tidak aktif
            //     //     const link = document.getElementById("link-selanjutnya");
            //     //     link.innerText = "Selanjutnya (Menunggu Konfirmasi)";
            //     //     link.classList.add("disabled");

            //     //     // Mulai polling untuk cek apakah admin sudah konfirmasi
            //     //     cekKonfirmasi();
            //     //     setInterval(cekKonfirmasi, 5000);
            //     // } else {
            //     //     alert("Gagal menyimpan data.");
            //     // }
            // });

            fetch("proses-simpan-responden.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.text()) // ambil response mentah
                .then(text => {
                    console.log("Raw response:", text); // bantu debug isi
                    const data = JSON.parse(text); // coba parsing ke JSON


                    if (data.status === 'success') {
                        const id_survei = data.id_survei;
                        const id_responden = data.id_responden;

                        // Start polling untuk cek konfirmasi
                        const interval = setInterval(() => {
                            fetch(`cek_konfirmasi.php?id_responden=${id_responden}`)
                                .then(res => res.json())
                                .then(resp => {
                                    if (resp.konfirmasi === 1) {
                                        clearInterval(interval);

                                        // Sembunyikan tombol tunggu
                                        waitingBtn.style.display = "none";

                                        // Tampilkan tombol selanjutnya
                                        const nextBtn = document.getElementById("next-button");
                                        nextBtn.href = `form-sus.php?id_survei=${id_survei}&id_responden=${id_responden}`;
                                        nextBtn.style.display = "inline-block";
                                    }
                                });
                        }, 5000);

                    } else {
                        alert("Gagal menyimpan data.");
                        // Tampilkan kembali form jika gagal
                        document.getElementById("checkbox-container").style.display = "block";
                        document.getElementById("btnSubmit").style.display = "inline-block";
                        waitingBtn.style.display = "none";
                    }
                })
                .catch(err => {
                    console.error("Fetch error:", err);
                    alert("Terjadi kesalahan jaringan.");
                    document.getElementById("checkbox-container").style.display = "block";
                    document.getElementById("btnSubmit").style.display = "inline-block";
                    waitingBtn.style.display = "none";
                });
        });

        function cekKonfirmasi() {
            const id_responden = window.respondenID;
            const id_survei = window.surveiID;

            fetch(`check-konfirmasi.php?id_responden=${id_responden}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === "success" && data.konfirmasi == 1) {
                        const link = document.getElementById("link-selanjutnya");
                        link.href = `form-sus.php?id_survei=${id_survei}&id_responden=${id_responden}`;
                        link.innerText = "Selanjutnya";
                        link.classList.remove("btn-secondary", "disabled");
                        link.classList.add("btn-primary");
                        // link.onclick = null;

                        // Hentikan polling setelah aktif
                        clearInterval(window.pollingInterval);
                    }
                });
        }
    </script>

</body>

</html>