<?php

$id_survei =  isset($_GET['id_survei']) ? (int) $_GET['id_survei'] : 0;
$id_login =  isset($_SESSION['id_login']) ? (int) $_SESSION['id_login'] : 0;

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

?>


<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Hasil Survei</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Detail Survei</a></li>
                    <li class="breadcrumb-item active">Hasil Survei</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) Informasi Singkat -->
        <div class="row">
            <!-- Col Total Responden -->
            <div class="col-lg-3 col-6">

                <!-- small box Total Responden -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="total-responden">0</h3>
                        <p>Responden</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col Total Responden -->

            <!-- Col Satisfaction -->
            <div class="col-lg-3 col-6">
                <?php
                $query = "SELECT AVG(total_skor_sus) as total_puas FROM hasil_skor_sus WHERE id_survei = $id_survei";
                $result = mysqli_query($conn, $query);
                $data = mysqli_fetch_assoc($result);

                $total_skor = isset($data['total_puas']) ? (float) $data['total_puas'] : 0.0;

                // Ambil kategori berdasarkan rata-rata skor
                $kategori_sus = getKategoriSUSDetail($total_skor);
                ?>
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= number_format($total_skor, 2); ?></h3>
                        <p>Aspek Satisfaction<sup class="text-gray font-weight-bold"> <abbr title="<?= $kategori_sus['adjective']; ?>" class="initialism">*Grade: <?= $kategori_sus['grade']; ?></abbr></sup></p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-thumbsup"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./Col Satisfaction -->

            <!-- Col Efficiency -->
            <div class="col-lg-3 col-6">
                <?php
                // Ambil data untuk tbeCard
                $queryTbeCard = mysqli_query($conn, "SELECT * FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_responden, id_task_skenario");

                $respondenTbeCard = [];
                $tasksTbeCard = [];
                $dataTbeCard = [];

                while ($row = mysqli_fetch_assoc($queryTbeCard)) {
                    $idResponden = $row['id_responden'];
                    $idTask = $row['id_task_skenario'];

                    $respondenTbeCard[$idResponden] = $row['nama_responden'];
                    $tasksTbeCard[$idTask] = $row['task_skenario'];

                    $dataTbeCard[$idResponden][$idTask] = [
                        'time_completion' => (float) $row['time_completion'],
                        'task_sukses' => (int) $row['task_sukses'],
                    ];
                }

                ksort($tasksTbeCard);
                $taskIdsTbeCard = array_keys($tasksTbeCard);
                $totalRespondenTbeCard = count($respondenTbeCard);
                $totalTasksTbeCard = count($tasksTbeCard);

                $sumTotalTbeCard = 0;
                foreach ($taskIdsTbeCard as $taskId) {
                    $sumTask = 0;
                    foreach ($respondenTbeCard as $id => $nama) {
                        if (isset($dataTbeCard[$id][$taskId])) {
                            $n = $dataTbeCard[$id][$taskId]['task_sukses'];
                            $t = $dataTbeCard[$id][$taskId]['time_completion'];
                            if ($t > 0) {
                                $sumTask += ($n / $t);
                            }
                        }
                    }
                    $sumTotalTbeCard += $sumTask;
                }

                $tbeCard = ($totalTasksTbeCard * $totalRespondenTbeCard > 0) ? round($sumTotalTbeCard / ($totalTasksTbeCard * $totalRespondenTbeCard), 4) : 0;
                ?>


                <!-- small box -->
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3><?= $tbeCard ?><small style="font-style: italic; font-size: 18px;"><span>goals/sec</span></small></h3>

                        <p>Aspek Efficiency</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-ios-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col Efficiency -->

            <!-- Col Task Sukses -->
            <div class="col-lg-3 col-6">
                <?php

                $total_success_card = 0;
                $total_task_card = 0;

                // Ambil semua task_sukses dari view
                $queryTaskSuksesCard = mysqli_query($conn, "SELECT task_sukses FROM v_respon_task_skenario WHERE id_survei = $id_survei");

                while ($rowTaskSuksesCard = mysqli_fetch_assoc($queryTaskSuksesCard)) {
                    $total_task_card++;
                    if ($rowTaskSuksesCard['task_sukses'] == 1) {
                        $total_success_card++;
                    }
                }

                $success_rate_card = ($total_task_card > 0) ? round(($total_success_card / $total_task_card) * 100, 2) : 0;
                ?>

                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $success_rate_card; ?>%</h3>

                        <p>Task Sukses</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- Task Sukses -->

        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6">
                <!-- card umur responden -->
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Distribusi Umur Responden</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php

                            $queryUmur = "SELECT umur, COUNT(*) as total FROM biodata_responden WHERE id_survei = $id_survei GROUP BY umur";
                            $resultUmur = mysqli_query($conn, $queryUmur);
                            $labelUmur = [];
                            $dataUmur = [];

                            while ($rowUmur = mysqli_fetch_assoc($resultUmur)) {
                                $labelUmur[] = html_entity_decode($rowUmur['umur']);
                                $dataUmur[] = (int)$rowUmur['total'];
                            }

                            // Kirim ke JavaScript
                            echo "<script>
                                var umurLabels = " . json_encode($labelUmur) . ";
                                var umurData = " . json_encode($dataUmur) . ";
                            </script>";

                            ?>
                            <!-- Pie Chart -->
                            <div class="col-12">
                                <div class="chart-responsive">
                                    <canvas id="Umur" height="150"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6 pt-3">
                                <ul class="chart-legend clearfix">
                                    <?php
                                    $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger', 'text-info', 'text-secondary', 'text-dark', 'text-muted', 'text-pink', 'text-orange'];
                                    foreach ($labelUmur as $i => $lblUmur) {
                                        $jumlahUmur = $dataUmur[$i];
                                        $colorUmur = $colors[$i % count($colors)];
                                        echo "<li><i class='fas fa-circle $colorUmur'></i> $lblUmur ($jumlahUmur)</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- card task -->
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <div class="card-header border-0">
                        <h3 class="card-title">Pekerjaan Responden</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php

                            $queryPekerjaan = "SELECT pekerjaan, COUNT(*) as total FROM biodata_responden WHERE id_survei = $id_survei GROUP BY pekerjaan";
                            $resultPekerjaan = mysqli_query($conn, $queryPekerjaan);
                            $labelPekerjaan = [];
                            $dataPekerjaan = [];

                            while ($rowPekerjaan = mysqli_fetch_assoc($resultPekerjaan)) {
                                $labelPekerjaan[] = $rowPekerjaan['pekerjaan'];
                                $dataPekerjaan[] = (int)$rowPekerjaan['total'];
                            }

                            // Kirim ke JavaScript
                            echo "<script>
                                var pekerjaanLabels = " . json_encode($labelPekerjaan) . ";
                                var pekerjaanData = " . json_encode($dataPekerjaan) . ";
                            </script>";
                            ?>
                            <!-- Pie Chart -->
                            <div class="col-12">
                                <div class="chart-responsive">
                                    <canvas id="Pekerjaan" height="150"></canvas>
                                </div>
                            </div>

                            <div class="col-md-6 pt-3">
                                <ul class="chart-legend clearfix">
                                    <?php
                                    $colors = ['text-primary', 'text-success', 'text-warning', 'text-danger', 'text-info', 'text-secondary', 'text-dark', 'text-muted', 'text-pink', 'text-orange'];
                                    foreach ($labelPekerjaan as $i => $lblPekerjaan) {
                                        $jumlah = $dataPekerjaan[$i];
                                        $color = $colors[$i % count($colors)];
                                        echo "<li><i class='fas fa-circle $color'></i> $lblPekerjaan ($jumlah)</li>";
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <section class="col-md-12">
                <!-- Card hasil Effectiveness -->
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-layer-group mr-1"></i>
                            <i>Effectiveness</i>
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="card shadow-none p-0">
                            <div class="card-header border-0">
                                <h3 class="card-title p-0">Success Rate</h3>
                            </div>
                            <div class="card-body">
                                <?php

                                $total_success_rate = 0;
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
                                ?>

                                <!-- Container Keterangan -->
                                <div class="alert alert-warning shadow">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i>Keterangan:</h5>
                                    1 = <span class="text-success"><strong>Berhasil</strong></span><br>
                                    0 = <span class="text-danger"><strong>Gagal</strong></span>
                                </div>

                                <table id="successrate" class="table table-bordered display nowrap table-striped" style="width:100%">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th width="10">No</th>
                                            <th>Nama Responden</th>
                                            <?php foreach ($tasks as $id_task => $nama_task): ?>
                                                <th>T<sub><?= $taskNumber++; ?></sub> <br>(<?php echo ucwords(htmlspecialchars($nama_task)); ?>)</th>
                                            <?php endforeach; ?>
                                            <th>Task Berhasil</th>
                                            <th>Total Task</th>
                                            <th>Success Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($respondents as $id => $respondent):
                                            // hitung success rate dari tiap responden dan simpan menjadi global success rate
                                            $success_rate = ($respondent['total'] > 0) ? round(($respondent['success'] / $respondent['total']) * 100, 2) : 0;
                                            $total_success_rate += $success_rate;

                                            // Ambil data pekerjaan
                                            $query_pekerjaan = "SELECT pekerjaan FROM v_respon_task_skenario WHERE id_responden = $id AND id_survei = $id_survei LIMIT 1";
                                            $result_pekerjaan = mysqli_query($conn, $query_pekerjaan);
                                            $pekerjaan = mysqli_fetch_assoc($result_pekerjaan);
                                        ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo htmlspecialchars($respondent['name']); ?></td>

                                                <?php foreach ($tasks as $id_task => $nama_task): ?>
                                                    <td class="<?php echo in_array($id_task, $respondent['tasks_completed']) ? 'completed fw-bold text-center' : 'text-center'; ?>">
                                                        <?php echo in_array($id_task, $respondent['tasks_completed']) ? ' (1) <span class="text-success">✓</span>' : ' (0) <span class="text-danger">✗</span>'; ?>
                                                    </td>
                                                <?php endforeach; ?>

                                                <td class="text-center"><?php echo $respondent['success']; ?></td>
                                                <td class="text-center"><?php echo $respondent['total']; ?></td>
                                                <td class="text-center"><?php echo $success_rate; ?>%</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <th colspan="<?php echo count($tasks) + 4; ?>"><strong>Rata-rata Success Rate</strong></th>
                                            <th colspan=""><strong><?php echo $total_respondents > 0 ? round($total_success_rate / $total_respondents, 2) : 0; ?>%</strong></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="card shadow-none p-0">
                            <div class="card-header border-0">
                                <h3 class="card-title p-0">Number of Error During Task Performance (Jumlah Salah Click)</h3>
                            </div>
                            <div class="card-body">
                                <?php
                                // Ambil semua task (distinct id_task_skenario + task_skenario)
                                $tasks = [];
                                $q_tasks = mysqli_query($conn, "SELECT DISTINCT id_task_skenario, task_skenario FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_task_skenario ASC");
                                while ($rowTask = mysqli_fetch_assoc($q_tasks)) {
                                    $tasks[$rowTask['id_task_skenario']] = $rowTask['task_skenario'];
                                }


                                // Ambil semua responden
                                $responden = [];
                                $q_responden = mysqli_query($conn, "SELECT DISTINCT id_responden, nama_responden FROM v_respon_task_skenario WHERE id_survei = $id_survei");
                                while ($rowRespondenErrorRate = mysqli_fetch_assoc($q_responden)) {
                                    $responden[$rowRespondenErrorRate['id_responden']] = $rowRespondenErrorRate['nama_responden'];
                                }


                                // Inisialisasi struktur data
                                $data_erro_rate = [];
                                $total_errors_per_task = array_fill_keys(array_keys($tasks), 0);
                                $total_responden = count($responden);

                                // Ambil semua data dari v_respon_task_skenario
                                $q_data = mysqli_query($conn, "SELECT * FROM v_respon_task_skenario WHERE id_survei = $id_survei");
                                while ($row = mysqli_fetch_assoc($q_data)) {
                                    $id_responden = $row['id_responden'];
                                    $id_task = $row['id_task_skenario'];
                                    $errors = (int)$row['number_errors'];

                                    $data_erro_rate[$id_responden]['nama'] = $row['nama_responden'];
                                    $data_erro_rate[$id_responden]['errors'][$id_task] = $errors;

                                    $total_errors_per_task[$id_task] += $errors;
                                }


                                // Hitung error rate per task skenario
                                $error_rates = [];
                                foreach ($tasks as $id_task => $task_name) {
                                    $error_rates[$id_task] = $total_responden > 0 ? ($total_errors_per_task[$id_task] / $total_responden) * 100 : 0;
                                }


                                // Hitung global error rate
                                $global_error_rate = count($tasks) > 0 ? array_sum($error_rates) / count($tasks) : 0;

                                // Buat task number
                                $taskNumber = 1;
                                ?>

                                <!-- Container Keterangan -->
                                <div class="alert alert-warning shadow">
                                    <h5><i class="icon fas fa-exclamation-triangle"></i>Keterangan:</h5>
                                    0 = <span class="text-success"><strong>Tidak ada kesalahan "click, salah pilih menu, dll"</strong></span><br>
                                    > 1 = <span class="text-danger"><strong>Terdapat ada kesalahan "click, salah pilih menu, dll"</strong></span>
                                </div>

                                <table id="numberoferrors" class="table table-bordered display nowrap table-striped" style="width:100%">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Survei</th>
                                            <?php foreach ($tasks as $task): ?>
                                                <th>T<sub><?= $taskNumber++; ?></sub> (<?= ucwords(htmlspecialchars($task)) ?>)</th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($responden as $id_responden => $nama): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($nama) ?></td>
                                                <?php foreach (array_keys($tasks) as $id_task): ?>
                                                    <td><?= $data_erro_rate[$id_responden]['errors'][$id_task] ?? 0 ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot class="table-success">
                                        <tr>
                                            <th colspan="2"><strong>Jumlah Error</strong></th>
                                            <?php foreach ($total_errors_per_task as $total): ?>
                                                <th><strong><?= $total ?></strong></th>
                                            <?php endforeach; ?>
                                        </tr>
                                        <tr>
                                            <th colspan="2"><strong>Error Rate (%)</strong></th>
                                            <?php foreach ($error_rates as $rate): ?>
                                                <th><strong><?= number_format($rate / 100, 2,) ?> (<?= number_format($rate, 2) ?>%)</strong></td>
                                                <?php endforeach; ?>
                                        </tr>
                                        <tr>
                                            <th colspan="2"><strong>Global Error Rate:</strong></th>
                                            <th colspan="<?= count($tasks) + 2 ?>"><strong> <?= number_format($global_error_rate / 100, 2) ?> (<?= number_format($global_error_rate, 2) ?>)%</strong></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>




                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.Card hasil Effectiveness -->

                <!-- Efficiency -->
                <div class="card card card-outline card-orange">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Skor Efficiency
                        </h3>
                        <!-- card tools -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <div class="card-body">
                        <?php
                        // Ambil data dari view
                        $queryTaskSkenarioTBE = mysqli_query($conn, "SELECT * FROM v_respon_task_skenario WHERE id_survei = $id_survei ORDER BY id_responden, id_task_skenario");

                        $respondenTBE = [];    // id_responden => nama_responden
                        $tasksTBE = [];        // id_task_skenario => task_skenario
                        $dataTBE = [];         // [id_responden][id_task_skenario] => [time_completion, task_sukses]

                        while ($rowTBE = mysqli_fetch_assoc($queryTaskSkenarioTBE)) {
                            $idRespondenTBE = $rowTBE['id_responden'];
                            $idTaskTBE = $rowTBE['id_task_skenario'];

                            $respondenTBE[$idRespondenTBE] = $rowTBE['nama_responden'];
                            $tasksTBE[$idTaskTBE] = $rowTBE['task_skenario'];

                            $dataTBE[$idRespondenTBE][$idTaskTBE] = [
                                'time_completion' => (float) $rowTBE['time_completion'],
                                'task_sukses' => (int) $rowTBE['task_sukses'],
                            ];
                        }

                        // Persiapan untuk perhitungan
                        ksort($tasksTBE); // urutkan task berdasarkan id
                        $tasksTBE_id = array_keys($tasksTBE);
                        $RTBE = count($respondenTBE);
                        $NTBE = count($tasksTBE);

                        $noTask = 1;
                        ?>

                        <!-- Container Keterangan -->
                        <div class="alert alert-warning shadow">
                            <h5><i class="icon fas fa-exclamation-triangle"></i>Keterangan:</h5>
                            1 = <span class="text-success"><strong>Berhasil</strong></span> ||
                            0 = <span class="text-danger"><strong>Gagal</strong></span>
                        </div>

                        <table id="efficiency" class="table table-bordered table-striped display nowrap" style="width:100%">
                            <thead class="bg-navy">
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <?php
                                    foreach ($tasksTBE as $taskName): ?>
                                        <th>T<sub><?= $noTask++; ?></sub> (<?= ucwords(htmlspecialchars($taskName)) ?>) <br>Waktu / "Gagal atau Berhasil"
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($respondenTBE as $id => $nama):
                                    echo "<tr>";
                                    echo "<td>$no</td><td>" . htmlspecialchars($nama) . "</td>";
                                    foreach ($tasksTBE_id as $taskIdTBE) {
                                        if (isset($dataTBE[$id][$taskIdTBE])) {
                                            $time = $dataTBE[$id][$taskIdTBE]['time_completion'];
                                            $success = $dataTBE[$id][$taskIdTBE]['task_sukses'];

                                            // Tampilkan simbol ✓ jika berhasil, ✗ jika gagal
                                            $symbol = ($success == 1) ? '<span class="text-success">✓</span>' : '<span class="text-danger">✗</span>';

                                            echo "<td>$time s / $success ($symbol)</td>";
                                        } else {
                                            echo "<td>-</td>";
                                        }
                                    }
                                    echo "</tr>";
                                    $no++;
                                endforeach;
                                ?>
                            </tbody>
                            <tfoot class="table-info">
                                <tr>
                                    <th colspan="2">Time-Based Efficiency per Task</th>
                                    <?php
                                    $sumTotalTBE = 0;
                                    foreach ($tasksTBE_id as $taskIdTBE) {
                                        $sumTaskTBE = 0;
                                        foreach ($respondenTBE as $idTBE => $nama) {
                                            if (isset($dataTBE[$idTBE][$taskIdTBE])) {
                                                $nTBE = $dataTBE[$idTBE][$taskIdTBE]['task_sukses'];
                                                $tTBE = $dataTBE[$idTBE][$taskIdTBE]['time_completion'];
                                                if ($tTBE > 0) {
                                                    $sumTaskTBE += ($nTBE / $tTBE);
                                                }
                                            }
                                        }
                                        $tbePerTask = ($RTBE > 0) ? round($sumTaskTBE / $RTBE, 4) : 0;
                                        $sumTotalTBE += $sumTaskTBE;
                                        echo "<th>$tbePerTask goals/sec</th>";
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <th colspan="2">Time-Based Efficiency Keseluruhan:</th>
                                    <th colspan="<?= count($tasksTBE) + 2 ?>" class="text-center">
                                        <?php
                                        $tbeTotal = ($NTBE * $RTBE > 0) ? round($sumTotalTBE / ($NTBE * $RTBE), 4) : 0;
                                        ?>
                                        <?= $tbeTotal; ?> goals/sec
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body-->
                </div>
                <!-- /.Efficiency -->

                <!-- Satisfaction -->
                <div class="card card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table mr-1"></i>
                            Skor Satisfaction
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-toggle="dropdown" title="Ekspor Data">
                                <i class="fas fa-download"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <a class="dropdown-item" href="#" onclick="$('#kepuasan1').DataTable().button(0).trigger();">
                                    <i class="fas fa-file-excel text-success"></i> Excel
                                </a>
                                <a class="dropdown-item" href="#" onclick="$('#kepuasan1').DataTable().button(1).trigger();">
                                    <i class="fas fa-file-csv text-info"></i> CSV
                                </a>
                                <a class="dropdown-item" href="#" onclick="$('#kepuasan1').DataTable().button(2).trigger();">
                                    <i class="fas fa-file-pdf text-danger"></i> PDF
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" onclick="$('#kepuasan1').DataTable().button(3).trigger();">
                                    <i class="fas fa-print text-primary"></i> Cetak
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php

                        $pernyataan = [];
                        $respondenData = [];
                        $totalSkorSUS = 0;
                        $totalRespondenSUS = 0;

                        // Ambil daftar pernyataan
                        $qPernyataan = mysqli_query($conn, "SELECT id_sus, nomor_pernyataan FROM v_survei_sus WHERE id_survei = $id_survei ORDER BY nomor_pernyataan ASC");
                        while ($row = mysqli_fetch_assoc($qPernyataan)) {
                            $pernyataan[] = $row;
                        }

                        // Ambil daftar responden
                        $qResponden = mysqli_query($conn, "SELECT id_responden, nama_responden FROM biodata_responden WHERE id_survei = $id_survei AND konfirmasi = 1");

                        // Looping respoden
                        while ($res = mysqli_fetch_assoc($qResponden)) {
                            $idResponden = $res['id_responden'];
                            $nama = $res['nama_responden'];

                            $jawaban = [];
                            $score = 0;
                            $isValid = true; // asumsi valid

                            // Ambil jawaban untuk semua pernyataan dari responden ini
                            foreach ($pernyataan as $p) {
                                $id_sus = $p['id_sus'];
                                $n = $p['nomor_pernyataan'];

                                $qJawaban = mysqli_query($conn, "SELECT answer FROM respon_sus WHERE id_survei_sus = $id_sus AND id_responden = $idResponden");
                                $dataJawaban = mysqli_fetch_assoc($qJawaban);

                                if (!isset($dataJawaban['answer']) || $dataJawaban['answer'] < 1 || $dataJawaban['answer'] > 5) {
                                    $isValid = false; // jawaban tidak valid, keluar dari perulangan
                                    break;
                                }

                                $answer = intval($dataJawaban['answer']);
                                $jawaban[] = $answer;

                                // Hitung skor tiap pernyataan 1-10 per Responden sebelum di kali 2.5
                                // Perhitungan SUS:
                                // Nomor ganjil: (jawaban - 1)
                                // Nomor genap: 5 - jawaban
                                $sus_score = ($n % 2 == 1) ? ($answer - 1) : (5 - $answer);
                                $score += $sus_score;
                            }

                            // Jika semua jawaban valid (lengkap dan antara 1-5)
                            if ($isValid && count($jawaban) === count($pernyataan)) {
                                // Final score dikali 2.5 (karena maksimal 40 x 2.5 = 100)
                                $skor_sus = $score * 2.5;
                                $totalSkorSUS += $skor_sus;
                                $totalRespondenSUS++;

                                // Simpan nama responden jawaban dan hasil skor sus/responden untuk ditampilkan kedalam tabel SUS
                                $respondenData[] = [
                                    'nama' => $nama,
                                    'jawaban' => $jawaban,
                                    'skor_sus' => $skor_sus
                                ];
                            }
                        }
                        ?>

                        <!-- Container Keterangan -->
                        <!-- <div class="alert alert-warning shadow">
                            <h5><i class="icon fas fa-exclamation-triangle"></i>Keterangan:</h5>
                            Data yang tampil pada tabel di bawah ini hanya data responden yang valid sudah mengisi Kuesioner SUS
                        </div> -->

                        <table id="kepuasan1" class="table table-bordered table-hover display nowrap" style="width:100%">
                            <thead class="bg-navy">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Responden</th>
                                    <?php foreach ($pernyataan as $p): ?>
                                        <th>P<?= $p['nomor_pernyataan']; ?></th>
                                    <?php endforeach; ?>
                                    <th>Skor Per Responden</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($respondenData as $index => $responden): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1; ?></td>
                                        <td><?= htmlspecialchars($responden['nama']); ?></td>
                                        <?php foreach ($responden['jawaban'] as $jawaban): ?>
                                            <td class="text-center"><?= $jawaban; ?></td>
                                        <?php endforeach; ?>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= number_format($responden['skor_sus'], 0); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>

                            <tfoot class="table-warning">
                                <tr>
                                    <th colspan="2" class="text-end">Rata-rata Keseluruhan Skor SUS</th>
                                    <?php foreach ($pernyataan as $p): ?>
                                        <th class=""></th>
                                    <?php endforeach; ?>

                                    <?php
                                    $perhitunganAkhirSUS = $totalRespondenSUS > 0 ? number_format($totalSkorSUS / $totalRespondenSUS, 2) : '0.00';
                                    $kategoriSUS = getKategoriSUSDetail($perhitunganAkhirSUS);

                                    ?>
                                    <th class="text-center"><?= $perhitunganAkhirSUS; ?>, <abbr title="Adj: <?= $kategori_sus['adjective']; ?> || NPS: <?= $kategori_sus['nps']; ?> || Acceptability: <?= $kategori_sus['nps']; ?>" class="initialism">*Grade: <?= $kategori_sus['grade']; ?></abbr></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <!-- /.card-body-->
                </div>
                <!-- ./Satisfaction -->

            </section>

        </div>
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    const idSurvei = <?= json_encode($_GET['id_survei'] ?? null) ?>;

    function loadTotalResponden() { //https://survei-usability-testing.trpl7b.my.id/admin/survei/

        fetch(`https://survei-usability-testing.trpl7b.my.id/admin/survei/get_responden.php?id_survei=${idSurvei}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }
                // Update info survei kanan
                document.getElementById('total-responden').textContent = data.total;
            })
            .catch(err => console.error('Error load responden:', err));
    }

    // Load pertama kali
    loadTotalResponden();

    // Refresh setiap 3 detik
    setInterval(loadTotalResponden, 1500);
</script>

<!-- Chart Pie Script -->
<script>
    var ctx = document.getElementById('Pekerjaan').getContext('2d');
    var taskPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Success', 'Fail', 'Abandon'],
            datasets: [{
                data: [38, 50, 13],
                backgroundColor: ['#28a745', '#f66c7b', '#17a2b8'],
            }]
        },
        options: {
            legend: {
                display: false // Legend sudah ada di samping, jadi disembunyikan
            }
        }
    });
</script>