<?php

$id_login = htmlspecialchars($_SESSION['id_login']);
$id_survei = htmlspecialchars($_GET['id_survei']);

// Query untuk info utama survei (satu baris)
$sqlInfoSurvei = "SELECT DISTINCT id_survei, title, survey_description, status, id_login, survey_created_at 
                  FROM v_survei_detail 
                  WHERE id_survei = ? AND id_login = ?";
$stmtInfo = $conn->prepare($sqlInfoSurvei);
$stmtInfo->bind_param("ii", $id_survei, $id_login);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();
$dataSurvei = $resultInfo->fetch_assoc();

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
                    <li class="breadcrumb-item active">Responden</li>
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
            <section class="col-md-9">
                <div id="containerForm">
                    <!-- Card pertama -->
                    <div class="card card-outline card-primary task-card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Survei anda : <?= htmlspecialchars($dataSurvei['title']) ?>
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool btn-collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-body">
                                <!-- Judul Survei -->
                                <div class="mb-3">
                                    <h3>Data Responden</h3>
                                </div>

                                <div class="table-responsive">
                                    <table id="tabelresponden" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Responden</th>
                                                <th>Waktu</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Kolom kanan -->
            <section class=" col-md-3">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Survei</h3>
                    </div>
                    <div class="card-body">
                        <?php
                        $sqlKeteranganSurvei =  mysqli_query($conn, "SELECT * FROM surveys WHERE id = $id_survei AND id_login = $id_login");
                        $resultKeteranganSurvei = mysqli_fetch_assoc($sqlKeteranganSurvei);

                        // Dari Database
                        $created_at = $resultKeteranganSurvei['created_at'];

                        // Ubah ke objek DateTime
                        $tanggal = new DateTime($created_at);

                        // Array bulan dalam Bahasa Indonesia
                        $bulanIndo = [
                            1 => 'Januari',
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

                        // Ambil tanggal, bulan, dan tahun
                        $hari = $tanggal->format('d');
                        $bulanAngka = (int) $tanggal->format('m');
                        $bulan = $bulanIndo[$bulanAngka]; // Ubah angka jadi nama bulan
                        $tahun = $tanggal->format('Y');
                        $jam = $tanggal->format('H:i');
                        ?>
                        <p><strong>Dibuat:</strong> <?= $hari; ?> <?= $bulan; ?> <?= $tahun; ?>, pukul <?= $jam; ?> WIB</p>

                        <?php

                        // Query untuk ambil total data responden
                        $sqlResponden = "SELECT * FROM biodata_responden WHERE id_survei = ?";
                        $stmtResponden = $conn->prepare($sqlResponden);
                        $stmtResponden->bind_param("i", $id_survei);
                        $stmtResponden->execute();
                        $resultRespondenTotal = $stmtResponden->get_result();

                        $totalResponden = 0;
                        $terkonfirmasi = 0;
                        $belumKonfirmasi = 0;

                        while ($responden = $resultRespondenTotal->fetch_assoc()) {

                            $totalResponden++;

                            if ($responden['konfirmasi'] == 1) {
                                $terkonfirmasi++;
                            } else {
                                $belumKonfirmasi++;
                            }
                        }
                        ?>


                        <p><strong>📊 Responden:</strong> <span id="total-responden"><?= $totalResponden ?></span></p>
                        <p><strong>✅ Sudah Konfirmasi:</strong> <span id="sudah-konfirmasi"><?= $terkonfirmasi ?></span></p>
                        <p><strong>⏳ Belum Konfirmasi:</strong> <span id="belum-konfirmasi"><?= $belumKonfirmasi ?></span></p>




                        <div class="d-grid gap-2">
                            <!-- Pengaturan -->
                            <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="btn btn-info btn-block mb-2"><i class="fas fa-eye"></i> Lihat Hasil</a>
                            <a href="#" class="btn btn-secondary btn-block" onclick="navigasibalik()"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </section>
        </div>


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    function navigasibalik() {
        window.history.back();
    }

    const idSurvei = <?= json_encode($_GET['id_survei'] ?? null) ?>;

    // let tableResponden = null;

    // Fungsi loadResponden
    // function loadResponden() { 

    //     fetch(`https://survei-usability-testing.trpl7b.my.id/admin/survei/get_responden.php?id_survei=${idSurvei}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.error) {
    //                 console.error(data.error);
    //                 return;
    //             }

    //             // Update tabel responden
    //             const tbody = document.getElementById('tbody-responden');
    //             tbody.innerHTML = ''; // kosongkan dulu

    //             let no = 1;
    //             data.responden.forEach(res => {
    //                 // Buat tombol aksi konfirmasi/detail
    //                 let aksiBtn = '';
    //                 if (res.konfirmasi !== 1) {
    //                     aksiBtn = `<a href="?page=tanggapi-responden&id_responden=${res.id_responden}&id_survei=${idSurvei}" class="btn btn-success btn-sm mr-2">
    //                                 <i class="fas fa-check-circle pr-1"></i>Konfirmasi
    //                             </a>`;
    //                 } else {
    //                     aksiBtn = `<a href="?page=detail-responden&id_responden=${res.id_responden}&id_survei=${idSurvei}" class="btn btn-info btn-sm mr-2">
    //                                 <i class="fas fa-eye pr-1"></i>Detail
    //                             </a>`;;
    //                 }

    //                 tbody.innerHTML +=
    //                     `<tr>
    //                     <td>${no++}</td>
    //                     <td>${res.nama_responden}</td>
    //                     <td>${res.waktu}</td>
    //                     <td>${res.status}</td>
    //                     <td><div class="d-flex">${aksiBtn}</div></td>
    //                 </tr>`;
    //             });

    //             // Update info survei kanan
    //             document.getElementById('total-responden').textContent = data.total;
    //             document.getElementById('sudah-konfirmasi').textContent = data.terkonfirmasi;
    //             document.getElementById('belum-konfirmasi').textContent = data.belumKonfirmasi;
    //         })
    //         .catch(err => console.error('Error load responden:', err));
    // }

    // // Load pertama kali
    // loadResponden();

    // // Refresh setiap 3 detik
    // setInterval(loadResponden, 3000);
</script>