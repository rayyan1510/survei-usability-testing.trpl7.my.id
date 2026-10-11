<?php

date_default_timezone_set("Asia/Jakarta"); // Set zona waktu penyambutan
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 12 && $hour < 17) {
    $greeting = "Selamat Siang";
} elseif ($hour >= 17 && $hour < 21) {
    $greeting = "Selamat Sore";
} else {
    $greeting = "Selamat Malam";
}


// menampilkan total survei
$sqlTotalSurvei = "SELECT v.name, COUNT(s.id) AS total_survei FROM v_akun_users v LEFT JOIN surveys s ON s.id_login = v.id_login WHERE v.id_login = ? GROUP BY v.name";

$stmtTotalSurvei = $conn->prepare($sqlTotalSurvei);
$stmtTotalSurvei->bind_param("i", $id_login);
$stmtTotalSurvei->execute();
$resultTotalSurvei =  $stmtTotalSurvei->get_result();
$dataTotalSurvei = $resultTotalSurvei->fetch_assoc();


?>


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bolder"><?= $greeting; ?>, <?= htmlspecialchars($userData['name']); ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
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
            <section class="col-12">
                <!-- card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- title -->
                            <div class="col-6">
                                <h4 class="mb-2">Survei Saya</h4>
                            </div>
                            <!-- button -->
                            <div class="col-6">
                                <a href="?page=buat-survey" class="btn btn-success float-sm-right"><i class="fas fa-plus"></i> Buat Survei</a>
                            </div>
                        </div>

                        <?php

                        $sqlSurveiDibuat = "
                                        SELECT 
                                            s.id,
                                            s.title,
                                            s.description,
                                            s.status,
                                            COUNT(*) AS total,
                                            s.created_at,
                                            COUNT(br.id_responden) AS total_responden
                                        FROM 
                                            surveys s
                                        LEFT JOIN 
                                            biodata_responden br ON s.id = br.id_survei
                                        WHERE 
                                            s.id_login = ?
                                        GROUP BY 
                                            s.id, s.title, s.description, s.status, s.created_at
                                        ORDER BY 
                                        s.created_at DESC
                                        ";

                        $stmtSurveiDibuat = $conn->prepare($sqlSurveiDibuat);
                        $stmtSurveiDibuat->bind_param("i", $id_login);
                        $stmtSurveiDibuat->execute();
                        $resultSurveiDibuat = $stmtSurveiDibuat->get_result();


                        // query untuk count total data
                        $sqlSurveiStatus = "
                            SELECT 
                                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS total_published,
                                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS total_draft,
                                SUM(CASE WHEN status = 'closed' THEN 1 ELSE 0 END) AS total_close
                            FROM 
                                surveys
                            WHERE 
                                id_login = ?
                        ";

                        $stmtSurveiStatus = $conn->prepare($sqlSurveiStatus);
                        $stmtSurveiStatus->bind_param("i", $id_login);
                        $stmtSurveiStatus->execute();
                        $resultSurveiStatus = $stmtSurveiStatus->get_result();
                        $rowSurveiStatus = $resultSurveiStatus->fetch_assoc();

                        // Simpan ke variabel
                        $total_published = $rowSurveiStatus['total_published'];
                        $total_draft = $rowSurveiStatus['total_draft'];
                        $total_close = $rowSurveiStatus['total_close'];

                        ?>

                        <!-- Nav Tab -->
                        <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custome-content-all-survei" data-toggle="pill"
                                    href="#custom-content-below-all-survei" role="tab" aria-controls="custom-content-below-all-survei"
                                    aria-selected="true">All (<?= $dataTotalSurvei['total_survei']; ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custome-content-draft-survei" data-toggle="pill"
                                    href="#custom-content-below-draft-survei" role="tab" aria-controls="custom-content-below-draft-survei"
                                    aria-selected="false">Draft (<?= $total_draft; ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-published-survei" data-toggle="pill"
                                    href="#custom-content-below-published-survei" role="tab" aria-controls="custom-content-below-published-survei"
                                    aria-selected="false">Published (<?= $total_published; ?>)</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-content-closed-survei" data-toggle="pill"
                                    href="#custom-content-below-closed-survei" role="tab" aria-controls="custom-content-below-closed-survei"
                                    aria-selected="false">Closed (<?= $total_close; ?>)</a>
                            </li>
                        </ul>
                        <!-- end Nav Tab -->

                        <!-- Nav tab content -->
                        <div class="tab-content" id="custom-content-below-tabContent">
                            <!-- All survei -->
                            <div class="tab-pane fade show active" id="custom-content-below-all-survei" role="tabpanel"
                                aria-labelledby="custome-content-all-survei">

                                <div class="card mb-3 shadow-sm">
                                    <?php
                                    // untuk menampilkan informasi singkat survei
                                    mysqli_data_seek($resultSurveiDibuat, 0);
                                    while ($rowDataSurveiAll = $resultSurveiDibuat->fetch_assoc()) {

                                    ?>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                <!-- Kiri -->
                                                <div>
                                                    <?php
                                                    if ($rowDataSurveiAll['status'] == 'draft') {
                                                        $statusBadgeSurvei = 'info';
                                                    } else if ($rowDataSurveiAll['status'] == 'published') {
                                                        $statusBadgeSurvei = 'success';
                                                    } else {
                                                        $statusBadgeSurvei = 'secondary';
                                                    }

                                                    ?>
                                                    <span class="badge badge-<?= $statusBadgeSurvei; ?>"><?= ucfirst($rowDataSurveiAll['status']); ?></span>
                                                    <h4 class="mt-2 mb-1">
                                                        <a href="?page=detail-survei&id_survei=<?= $rowDataSurveiAll['id']; ?>" class="text-dark text-decoration-none">
                                                            <?= htmlspecialchars($rowDataSurveiAll['title']); ?>
                                                        </a>
                                                    </h4>
                                                    <div class="text-muted small">
                                                        <span>Dibuat: <strong><?= date('d-m-Y H:i', strtotime($rowDataSurveiAll['created_at'])) ?></strong></span>
                                                    </div>
                                                </div>

                                                <!-- Kanan -->
                                                <div class="text-right">
                                                    <ul class="list-inline mb-0 d-flex flex-wrap align-items-center">
                                                        <!-- Task Sukses -->
                                                        <?php

                                                        // ambil id survei
                                                        $id_survei = $rowDataSurveiAll['id'];

                                                        $total_success_rate = 0;
                                                        $total_failure_rate = 0;
                                                        $total_respondents = 0;

                                                        // Ambil task berdasarkan survei
                                                        $tasks = [];
                                                        $q_task = mysqli_query($conn, "SELECT DISTINCT id_task_skenario, task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_task_skenario");
                                                        while ($row = mysqli_fetch_assoc($q_task)) {
                                                            $tasks[$row['id_task_skenario']] = $row['task_skenario'];
                                                        }

                                                        // Ambil data responden dan task mereka
                                                        $respondents = [];
                                                        $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");

                                                        while ($r = mysqli_fetch_assoc($q_responden)) {
                                                            $id_responden = $r['id_responden'];
                                                            $nama = $r['nama_responden'];

                                                            $respondents[$id_responden] = [
                                                                'name' => $nama,
                                                                'tasks_completed' => [],
                                                                'success' => 0,
                                                                'total' => 0
                                                            ];


                                                            // lakukan inisialisasi data success dan taks_completed
                                                            $q_tasks = mysqli_query($conn, "SELECT id_task_skenario, task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei AND id_responden = $id_responden");
                                                            while ($t = mysqli_fetch_assoc($q_tasks)) {
                                                                $respondents[$id_responden]['total']++;
                                                                if ($t['task_sukses'] == 1) {
                                                                    $respondents[$id_responden]['success']++;
                                                                    $respondents[$id_responden]['tasks_completed'][] = $t['id_task_skenario'];
                                                                }
                                                            }
                                                        }

                                                        $total_respondents = count($respondents);
                                                        $taskNumber = 1;

                                                        $no = 1;
                                                        foreach ($respondents as $id => $respondent) {
                                                            // hitung success rate dari tiap responden dan simpan menjadi global success rate
                                                            $success_rate = ($respondent['total'] > 0) ? round(($respondent['success'] / $respondent['total']) * 100, 2) : 0;
                                                            $total_success_rate += $success_rate;

                                                            $sukses = $respondent['success'];
                                                            $totalResponden = $respondent['total'];
                                                            $failure = $totalResponden - $sukses;
                                                            $failure_rate = ($totalResponden > 0) ? round(($failure / $totalResponden) * 100, 2) : 0;
                                                            $total_failure_rate += $failure_rate;
                                                        }

                                                        // hitung total rata rata sukses
                                                        $tampil_total_keberhasilan = ($total_respondents > 0) ? round($total_success_rate / $total_respondents, 2) : 0;
                                                        // hitung total rata rata kegagalan
                                                        $tampil_total_kegagalan = ($total_respondents > 0) ? round($total_failure_rate / $total_respondents, 2) : 0;

                                                        // Aspek Kepuasan
                                                        $querySus = "SELECT AVG(total_skor_sus) as total_puas FROM hasil_skor_sus WHERE id_survei = $id_survei";
                                                        $resultSus = mysqli_query($conn, $querySus);
                                                        $dataSus = mysqli_fetch_assoc($resultSus);

                                                        $total_skor_sus = $dataSus['total_puas'];

                                                        // Ambil kategori berdasarkan rata-rata skor
                                                        ?>
                                                        <li class="list-inline-item text-center mx-4">
                                                            <strong class="d-block h4"><?= $tampil_total_keberhasilan; ?> %</strong>
                                                            <small class="text-muted">Task Sukses</small>
                                                        </li>
                                                        <li class="list-inline-item text-center mx-4">
                                                            <strong class="d-block h4 text-danger"><?= $tampil_total_kegagalan; ?> %</strong>
                                                            <small class="text-muted">Task Gagal</small>
                                                        </li>
                                                        <li class="list-inline-item text-center mx-4">
                                                            <strong class="d-block h4 text-info"><?= isset($dataSus['total_puas']) && $dataSus['total_puas'] !== null ? number_format($dataSus['total_puas'], 2) : '0.00'; ?>
                                                            </strong>
                                                            <small class="text-muted">SUS</small>
                                                        </li>
                                                        <li class="list-inline-item text-center mx-4">
                                                            <strong class="d-block h4"><?= $total_respondents; ?></strong>
                                                            <small class="text-muted">Partisipan/Responden</small>
                                                        </li>
                                                        <li class="list-inline-item text-center mx-4">
                                                            <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="text-secondary d-block">
                                                                <i class="far fa-file-alt fa-lg"></i>
                                                                <small class="d-block">View Report</small>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item text-center mx-4 dropdown">
                                                            <a href="#" class="text-secondary d-block dropdown-toggle" role="button" id="dropdownMore<?= $id_survei; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-h fa-lg"></i>
                                                                <small class="d-block">More</small>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMore<?= $id_survei; ?>">
                                                                <a class="dropdown-item" href="?page=form-edit-survei&id_survei=<?= $id_survei; ?>">
                                                                    <i class="fas fa-edit mr-2"></i> Edit Survei
                                                                </a>
                                                                <!-- Tambahkan opsi lain di sini jika perlu -->
                                                                <a class="dropdown-item" href="#" onclick="hapusSurveiButton(<?= $id_survei; ?>)">
                                                                    <i class="fas fa-trash mr-2"></i> Hapus Survei
                                                                </a>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    <?php } ?>
                                </div>

                            </div>

                            <!-- Draft Survei -->
                            <div class="tab-pane fade" id="custom-content-below-draft-survei" role="tabpanel"
                                aria-labelledby="custome-content-draft-survei">

                                <div class="card mb-3 shadow-sm">
                                    <?php
                                    // Reset pointer hasil query
                                    mysqli_data_seek($resultSurveiDibuat, 0);
                                    $adaSurveiDraft = false;

                                    while ($rowDataSurveiDraft = $resultSurveiDibuat->fetch_assoc()) {
                                        if ($rowDataSurveiDraft['status'] == 'draft') {
                                            $adaSurveiDraft = true;
                                    ?>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <!-- Kiri -->
                                                    <div>
                                                        <span class="badge badge-info"><?= ucfirst($rowDataSurveiDraft['status']); ?></span>
                                                        <h4 class="mt-2 mb-1">
                                                            <a href="?page=detail-survei&id_survei=<?= $rowDataSurveiDraft['id']; ?>" class="text-dark text-decoration-none">
                                                                <?= htmlspecialchars($rowDataSurveiDraft['title']); ?>
                                                            </a>
                                                        </h4>
                                                        <div class="text-muted small">
                                                            <span>Dibuat: <strong><?= date('d-m-Y H:i', strtotime($rowDataSurveiDraft['created_at'])) ?></strong></span>
                                                        </div>
                                                    </div>

                                                    <!-- Kanan -->
                                                    <div class="text-right">
                                                        <?php

                                                        // ambil id survei
                                                        $id_survei = $rowDataSurveiDraft['id'];

                                                        $total_success_rate = 0;
                                                        $total_failure_rate = 0;
                                                        $total_respondents = 0;

                                                        // Ambil task berdasarkan survei
                                                        $tasks = [];
                                                        $q_task = mysqli_query($conn, "SELECT DISTINCT id_task_skenario, task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_task_skenario");
                                                        while ($row = mysqli_fetch_assoc($q_task)) {
                                                            $tasks[$row['id_task_skenario']] = $row['task_skenario'];
                                                        }

                                                        // Ambil data responden dan task mereka
                                                        $respondents = [];
                                                        $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");

                                                        while ($r = mysqli_fetch_assoc($q_responden)) {
                                                            $id_responden = $r['id_responden'];
                                                            $nama = $r['nama_responden'];

                                                            $respondents[$id_responden] = [
                                                                'name' => $nama,
                                                                'tasks_completed' => [],
                                                                'success' => 0,
                                                                'total' => 0
                                                            ];


                                                            // lakukan inisialisasi data success dan taks_completed
                                                            $q_tasks = mysqli_query($conn, "SELECT id_task_skenario, task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei AND id_responden = $id_responden");
                                                            while ($t = mysqli_fetch_assoc($q_tasks)) {
                                                                $respondents[$id_responden]['total']++;
                                                                if ($t['task_sukses'] == 1) {
                                                                    $respondents[$id_responden]['success']++;
                                                                    $respondents[$id_responden]['tasks_completed'][] = $t['id_task_skenario'];
                                                                }
                                                            }
                                                        }

                                                        $total_respondents = count($respondents);
                                                        $taskNumber = 1;

                                                        $no = 1;
                                                        foreach ($respondents as $id => $respondent) {
                                                            // hitung success rate dari tiap responden dan simpan menjadi global success rate
                                                            $success_rate = ($respondent['total'] > 0) ? round(($respondent['success'] / $respondent['total']) * 100, 2) : 0;
                                                            $total_success_rate += $success_rate;

                                                            $sukses = $respondent['success'];
                                                            $totalResponden = $respondent['total'];
                                                            $failure = $totalResponden - $sukses;
                                                            $failure_rate = ($totalResponden > 0) ? round(($failure / $totalResponden) * 100, 2) : 0;
                                                            $total_failure_rate += $failure_rate;
                                                        }

                                                        // hitung total rata rata sukses
                                                        $tampil_total_keberhasilan = ($total_respondents > 0) ? round($total_success_rate / $total_respondents, 2) : 0;
                                                        // hitung total rata rata kegagalan
                                                        $tampil_total_kegagalan = ($total_respondents > 0) ? round($total_failure_rate / $total_respondents, 2) : 0;

                                                        // Aspek Kepuasan
                                                        $querySus = "SELECT AVG(total_skor_sus) as total_puas FROM hasil_skor_sus WHERE id_survei = $id_survei";
                                                        $resultSus = mysqli_query($conn, $querySus);
                                                        $dataSus = mysqli_fetch_assoc($resultSus);

                                                        $total_skor_sus = $dataSus['total_puas'];

                                                        ?>
                                                        <ul class="list-inline mb-0 d-flex flex-wrap align-items-center">
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $tampil_total_keberhasilan; ?> %</strong>
                                                                <small class="text-muted">Task Sukses</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-danger"><?= $tampil_total_kegagalan; ?> %</strong>
                                                                <small class="text-muted">Task Gagal</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-info"><?= isset($dataSus['total_puas']) && $dataSus['total_puas'] !== null ? number_format($dataSus['total_puas'], 2) : '0.00'; ?>
                                                                </strong>
                                                                <small class="text-muted">SUS</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $total_respondents; ?></strong>
                                                                <small class="text-muted">Partisipan/Responden</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="text-secondary d-block">
                                                                    <i class="far fa-file-alt fa-lg"></i>
                                                                    <small class="d-block">View Report</small>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4 dropdown">
                                                                <a href="#" class="text-secondary d-block dropdown-toggle" role="button" id="dropdownMore<?= $id_survei; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-h fa-lg"></i>
                                                                    <small class="d-block">More</small>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMore<?= $id_survei; ?>">
                                                                    <a class="dropdown-item" href="?page=form-edit-survei&id_survei=<?= $id_survei; ?>">
                                                                        <i class="fas fa-edit mr-2"></i> Edit Survei
                                                                    </a>
                                                                    <!-- Tambahkan opsi lain di sini jika perlu -->
                                                                    <a class="dropdown-item" href="#" onclick="hapusSurveiButton(<?= $id_survei; ?>)">
                                                                        <i class="fas fa-trash mr-2"></i> Hapus Survei
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        <?php
                                        }
                                    }

                                    // Tampilkan pesan jika tidak ada survei draft
                                    if (!$adaSurveiDraft) {
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-center align-items-center flex-wrap py-5">
                                                <h5 class="text-muted">Belum ada survei dalam status draft.</h5>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                            <!-- Published Survei -->
                            <div class="tab-pane fade" id="custom-content-below-published-survei" role="tabpanel"
                                aria-labelledby="custom-content-published-survei">

                                <div class="card mb-3 shadow-sm">
                                    <?php
                                    mysqli_data_seek($resultSurveiDibuat, 0);
                                    $adaSurveiPublished = false;

                                    while ($rowDataSurveiPublished = $resultSurveiDibuat->fetch_assoc()) {
                                        if ($rowDataSurveiPublished['status'] == 'published') {
                                            $adaSurveiPublished = true;
                                    ?>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <!-- Kiri -->
                                                    <div>
                                                        <?php
                                                        $statusBadgeSurvei = 'success';
                                                        ?>
                                                        <span class="badge badge-<?= $statusBadgeSurvei; ?>">
                                                            <?= ucfirst($rowDataSurveiPublished['status']); ?>
                                                        </span>
                                                        <h4 class="mt-2 mb-1">
                                                            <a href="?page=detail-survei&id_survei=<?= $rowDataSurveiPublished['id']; ?>" class="text-dark text-decoration-none">
                                                                <?= htmlspecialchars($rowDataSurveiPublished['title']); ?>
                                                            </a>
                                                        </h4>
                                                        <div class="text-muted small">
                                                            <span>Dibuat: <strong><?= date('d-m-Y H:i', strtotime($rowDataSurveiPublished['created_at'])) ?></strong></span>
                                                        </div>
                                                    </div>

                                                    <!-- Kanan -->
                                                    <div class="text-right">
                                                        <ul class="list-inline mb-0 d-flex flex-wrap align-items-center">
                                                            <!-- Task Sukses -->
                                                            <?php

                                                            // ambil id survei
                                                            $id_survei = $rowDataSurveiPublished['id'];

                                                            $total_success_rate = 0;
                                                            $total_failure_rate = 0;
                                                            $total_respondents = 0;

                                                            // Ambil task berdasarkan survei
                                                            $tasks = [];
                                                            $q_task = mysqli_query($conn, "SELECT DISTINCT id_task_skenario, task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_task_skenario");
                                                            while ($row = mysqli_fetch_assoc($q_task)) {
                                                                $tasks[$row['id_task_skenario']] = $row['task_skenario'];
                                                            }

                                                            // Ambil data responden dan task mereka
                                                            $respondents = [];
                                                            $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");

                                                            while ($r = mysqli_fetch_assoc($q_responden)) {
                                                                $id_responden = $r['id_responden'];
                                                                $nama = $r['nama_responden'];

                                                                $respondents[$id_responden] = [
                                                                    'name' => $nama,
                                                                    'tasks_completed' => [],
                                                                    'success' => 0,
                                                                    'total' => 0
                                                                ];


                                                                // lakukan inisialisasi data success dan taks_completed
                                                                $q_tasks = mysqli_query($conn, "SELECT id_task_skenario, task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei AND id_responden = $id_responden");
                                                                while ($t = mysqli_fetch_assoc($q_tasks)) {
                                                                    $respondents[$id_responden]['total']++;
                                                                    if ($t['task_sukses'] == 1) {
                                                                        $respondents[$id_responden]['success']++;
                                                                        $respondents[$id_responden]['tasks_completed'][] = $t['id_task_skenario'];
                                                                    }
                                                                }
                                                            }

                                                            $total_respondents = count($respondents);
                                                            $taskNumber = 1;

                                                            $no = 1;
                                                            foreach ($respondents as $id => $respondent) {
                                                                // hitung success rate dari tiap responden dan simpan menjadi global success rate
                                                                $success_rate = ($respondent['total'] > 0) ? round(($respondent['success'] / $respondent['total']) * 100, 2) : 0;
                                                                $total_success_rate += $success_rate;

                                                                $sukses = $respondent['success'];
                                                                $totalResponden = $respondent['total'];
                                                                $failure = $totalResponden - $sukses;
                                                                $failure_rate = ($totalResponden > 0) ? round(($failure / $totalResponden) * 100, 2) : 0;
                                                                $total_failure_rate += $failure_rate;
                                                            }

                                                            // hitung total rata rata sukses
                                                            $tampil_total_keberhasilan = ($total_respondents > 0) ? round($total_success_rate / $total_respondents, 2) : 0;
                                                            // hitung total rata rata kegagalan
                                                            $tampil_total_kegagalan = ($total_respondents > 0) ? round($total_failure_rate / $total_respondents, 2) : 0;

                                                            // Aspek Kepuasan
                                                            $querySus = "SELECT AVG(total_skor_sus) as total_puas FROM hasil_skor_sus WHERE id_survei = $id_survei";
                                                            $resultSus = mysqli_query($conn, $querySus);
                                                            $dataSus = mysqli_fetch_assoc($resultSus);

                                                            $total_skor_sus = $dataSus['total_puas'];

                                                            // Ambil kategori berdasarkan rata-rata skor
                                                            // $kategori_sus = getKategoriSUS($total_skor_sus);
                                                            ?>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $tampil_total_keberhasilan; ?> %</strong>
                                                                <small class="text-muted">Task Sukses</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-danger"><?= $tampil_total_kegagalan; ?> %</strong>
                                                                <small class="text-muted">Task Gagal</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-info"><?= isset($dataSus['total_puas']) && $dataSus['total_puas'] !== null ? number_format($dataSus['total_puas'], 2) : '0.00'; ?>
                                                                </strong>
                                                                <small class="text-muted">SUS</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $total_respondents; ?></strong>
                                                                <small class="text-muted">Partisipan/Responden</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="text-secondary d-block">
                                                                    <i class="far fa-file-alt fa-lg"></i>
                                                                    <small class="d-block">View Report</small>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4 dropdown">
                                                                <a href="#" class="text-secondary d-block dropdown-toggle" role="button" id="dropdownMore<?= $id_survei; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-h fa-lg"></i>
                                                                    <small class="d-block">More</small>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMore<?= $id_survei; ?>">
                                                                    <a class="dropdown-item" href="?page=form-edit-survei&id_survei=<?= $id_survei; ?>">
                                                                        <i class="fas fa-edit mr-2"></i> Edit Survei
                                                                    </a>
                                                                    <!-- Tambahkan opsi lain di sini jika perlu -->
                                                                    <a class="dropdown-item" href="#" onclick="hapusSurveiButton(<?= $id_survei; ?>)">
                                                                        <i class="fas fa-trash mr-2"></i> Hapus Survei
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        <?php
                                        }
                                    }

                                    // Tampilkan pesan jika tidak ada survei published
                                    if (!$adaSurveiPublished) {
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-center align-items-center flex-wrap py-5">
                                                <h5 class="text-muted">Belum ada survei yang dipublikasikan.</h5>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                            </div>

                            <!-- Closed Survei -->
                            <div class="tab-pane fade" id="custom-content-below-closed-survei" role="tabpanel"
                                aria-labelledby="custom-content-closed-survei">

                                <div class="card mb-3 shadow-sm">
                                    <?php
                                    mysqli_data_seek($resultSurveiDibuat, 0); // Reset pointer data
                                    $adaSurveiClosed = false;

                                    while ($rowDataSurveiTutup = $resultSurveiDibuat->fetch_assoc()) {
                                        if ($rowDataSurveiTutup['status'] == 'closed') {
                                            $adaSurveiClosed = true; // tandai bahwa ada survei closed
                                    ?>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap">
                                                    <!-- Kiri -->
                                                    <div>
                                                        <?php
                                                        $statusBadgeSurvei = match ($rowDataSurveiTutup['status']) {
                                                            'draft' => 'info',
                                                            'published' => 'success',
                                                            default => 'secondary'
                                                        };
                                                        ?>
                                                        <span class="badge badge-<?= $statusBadgeSurvei; ?>">
                                                            <?= ucfirst($rowDataSurveiTutup['status']); ?>
                                                        </span>
                                                        <h4 class="mt-2 mb-1">
                                                            <a href="?page=detail-survei&id_survei=<?= $rowDataSurveiTutup['id']; ?>" class="text-dark text-decoration-none">
                                                                <?= htmlspecialchars($rowDataSurveiTutup['title']); ?>
                                                            </a>
                                                        </h4>
                                                        <div class="text-muted small">
                                                            <span>Dibuat: <strong><?= date('d-m-Y H:i', strtotime($rowDataSurveiTutup['created_at'])) ?></strong></span>
                                                        </div>
                                                    </div>

                                                    <!-- Kanan -->
                                                    <div class="text-right">
                                                        <ul class="list-inline mb-0 d-flex flex-wrap align-items-center">

                                                            <?php
                                                            $id_survei = $rowDataSurveiTutup['id'];

                                                            $total_success_rate = 0;
                                                            $total_failure_rate = 0;
                                                            $total_respondents = 0;

                                                            // Ambil task
                                                            $tasks = [];
                                                            $q_task = mysqli_query($conn, "SELECT DISTINCT id_task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei");
                                                            while ($row = mysqli_fetch_assoc($q_task)) {
                                                                $tasks[] = $row['id_task_skenario'];
                                                            }

                                                            // Ambil responden
                                                            $respondents = [];
                                                            $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");
                                                            while ($r = mysqli_fetch_assoc($q_responden)) {
                                                                $id_responden = $r['id_responden'];
                                                                $respondents[$id_responden] = ['success' => 0, 'total' => 0];

                                                                $q_tasks = mysqli_query($conn, "SELECT task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei AND id_responden = $id_responden");
                                                                while ($t = mysqli_fetch_assoc($q_tasks)) {
                                                                    $respondents[$id_responden]['total']++;
                                                                    if ($t['task_sukses'] == 1) {
                                                                        $respondents[$id_responden]['success']++;
                                                                    }
                                                                }
                                                            }

                                                            $total_respondents = count($respondents);

                                                            foreach ($respondents as $responden) {
                                                                $sukses = $responden['success'];
                                                                $total = $responden['total'];
                                                                $gagal = $total - $sukses;

                                                                $total_success_rate += ($total > 0) ? round(($sukses / $total) * 100, 2) : 0;
                                                                $total_failure_rate += ($total > 0) ? round(($gagal / $total) * 100, 2) : 0;
                                                            }

                                                            $tampil_total_keberhasilan = ($total_respondents > 0) ? round($total_success_rate / $total_respondents, 2) : 0;
                                                            $tampil_total_kegagalan = ($total_respondents > 0) ? round($total_failure_rate / $total_respondents, 2) : 0;

                                                            // SUS
                                                            $querySus = "SELECT AVG(total_skor_sus) as total_puas FROM hasil_skor_sus WHERE id_survei = $id_survei";
                                                            $resultSus = mysqli_query($conn, $querySus);
                                                            $dataSus = mysqli_fetch_assoc($resultSus);
                                                            $total_skor_sus = number_format($dataSus['total_puas'], 2);
                                                            ?>

                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $tampil_total_keberhasilan; ?> %</strong>
                                                                <small class="text-muted">Task Sukses</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-danger"><?= $tampil_total_kegagalan; ?> %</strong>
                                                                <small class="text-muted">Task Gagal</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4 text-info"><?= $total_skor_sus; ?></strong>
                                                                <small class="text-muted">SUS</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <strong class="d-block h4"><?= $total_respondents; ?></strong>
                                                                <small class="text-muted">Partisipan</small>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4">
                                                                <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="text-secondary d-block">
                                                                    <i class="far fa-file-alt fa-lg"></i>
                                                                    <small class="d-block">View Report</small>
                                                                </a>
                                                            </li>
                                                            <li class="list-inline-item text-center mx-4 dropdown">
                                                                <a href="#" class="text-secondary d-block dropdown-toggle" role="button" id="dropdownMore<?= $id_survei; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="fas fa-ellipsis-h fa-lg"></i>
                                                                    <small class="d-block">More</small>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMore<?= $id_survei; ?>">
                                                                    <a class="dropdown-item" href="?page=form-edit-survei&id_survei=<?= $id_survei; ?>">
                                                                        <i class="fas fa-edit mr-2"></i> Edit Survei
                                                                    </a>
                                                                    <!-- Tambahkan opsi lain di sini jika perlu -->
                                                                    <a class="dropdown-item" href="#" onclick="hapusSurveiButton(<?= $id_survei; ?>)">
                                                                        <i class="fas fa-trash mr-2"></i> Hapus Survei
                                                                    </a>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        <?php
                                        }
                                    }

                                    // tampilkan pesan jika tidak ada survei closed
                                    if (!$adaSurveiClosed) {
                                        ?>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-center align-items-center flex-wrap py-5">
                                                <h5 class="text-muted">Belum ada survei yang ditutup.</h5>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                        <!-- end Nav tab content -->



                    </div>


                </div>
                <!-- /.card-body-->
            </section>
        </div>
        <!-- /.card -->

</section>
<!-- /.content -->

<script>
    // Fungsi hapus survei
    function hapusSurveiButton(idSurvei) {
        Swal.fire({
            title: 'Yakin ingin menghapus survei?',
            text: "Semua data yang berkaitan dengan survei ini akan ikut terhapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke halaman PHP yang akan handle penghapusan
                window.location.href = "?page=delete-survei&id_survei=" + idSurvei + "&id_login=<?= $id_login; ?>";
            }
        });
    }
</script>