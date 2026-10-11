<?php

// var_dump($_GET);

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


// Query untuk ambil semua task & pernyataan SUS
$sqlSemuaDetail = "SELECT * FROM v_survei_detail WHERE id_survei = ? AND id_login = ?";
$stmtDetail = $conn->prepare($sqlSemuaDetail);
$stmtDetail->bind_param("ii", $id_survei, $id_login);
$stmtDetail->execute();
$resultDetail = $stmtDetail->get_result();

$tasks = [];
$sus = [];

while ($row = $resultDetail->fetch_assoc()) {
    if ($row['task_skenario']) {
        $tasks[$row['id_task']] = [
            'id_task' => $row['id_task'],
            'task_skenario' => $row['task_skenario'],
            'task_deskripsi' => $row['task_description']
        ]; // hindari duplikat
    }
    if ($row['id_sus']) {
        $sus[$row['nomor_pernyataan']] = [
            'id_sus' => $row['id_sus'],
            'pernyataan' => $row['pernyataan'],
            'positif' => $row['is_positif']
        ];
    }
}


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
                    <li class="breadcrumb-item active">
                        <?= htmlspecialchars($dataSurvei['title']) ?>
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
            <!-- Kolom kiri -->
            <section class="col-lg-8 col-md-12">
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
                                    <h3><strong><?= htmlspecialchars($dataSurvei['title']) ?></strong></h3>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-3">
                                    <h6>Deskripsi Survei</h6>
                                    <div class="text-muted m-0">
                                        <?= $dataSurvei['survey_description'] ?>
                                    </div>
                                </div>

                                <!-- Accordion -->
                                <div id="accordion">
                                    <!-- Task Skenario -->
                                    <div class="card card-primary">
                                        <div class="card-header bg-navy">
                                            <h4 class="card-title w-100">
                                                <a href="#collapseOne" class="d-block w-100" data-toggle="collapse">
                                                    Pratinjau Task Skenario
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                            <div class="card-body">
                                                <a href="?page=task-skenario&id_survei=<?= $id_survei; ?>" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Task Skenario</a>
                                                <?php if (empty($tasks)) { ?>
                                                    <div class="alert alert-warning">
                                                        Maaf Kamu belum buat task skenario yang akan di ujikan oleh user.
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="table-responsive">
                                                        <table id="survei1" class="table table-bordered table-striped" style="width: 100%;">
                                                            <thead>
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Task</th>
                                                                    <th>Deskripsi</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $no = 1;
                                                                foreach ($tasks as $task) { ?>
                                                                    <tr>
                                                                        <td><?= $no++; ?></td>
                                                                        <td><?= htmlspecialchars($task['task_skenario']); ?></td>
                                                                        <td><?= $task['task_deskripsi']; ?></td>
                                                                        <td>
                                                                            <div class="d-flex">
                                                                                <a href="?page=form-edit-task-skenario&id_survei=<?= $id_survei; ?>&id_task=<?= $task['id_task']; ?>" class="btn btn-warning btn-sm mr-2"><i class="fas fa-edit"></i> Edit</a>
                                                                                <a href="#" class="btn btn-danger btn-sm" onclick="hapusTaskSkenarioButton(<?= $task['id_task']; ?>)"><i class="fas fa-trash"></i> Hapus</a>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ./Task Skenario -->

                                    <!-- Kuesioner -->
                                    <div class="card card-primary">
                                        <div class="card-header bg-navy">
                                            <h4 class="card-title w-100">
                                                <a href="#collapseTwo" class="d-block w-100" data-toggle="collapse">
                                                    Pratinjau Kuesioner SUS (System Usability Scale)
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapseTwo" class="collapse show" data-parent="#accordion">
                                            <div class="card-body">
                                                <?php if (empty($sus)) : ?>
                                                    <a href="?page=form-sus&id_survei=<?= $id_survei; ?>" class="btn btn-success mb-3">
                                                        <i class="fas fa-plus"></i> Tambah Pernyataan Kuesioner SUS (System Usability Scale)
                                                    </a>
                                                    <div class="alert alert-warning">
                                                        Maaf Kamu belum buat Pernyataan Kuesioner SUS (System Usability Scale) yang akan di ujikan oleh user.
                                                    </div>
                                                <?php else: ?>
                                                    <ol style="padding-left: 20px;">
                                                        <?php foreach ($sus as $nomor => $item): ?>
                                                            <li style="margin-bottom: 25px;">
                                                                <div style="font-size: 16px;"><?= htmlspecialchars($item['pernyataan']) ?></div>
                                                                <div style="font-size: 14px; color: #555;">
                                                                    <em>Tipe: <strong><?= $item['positif'] ? 'Positif' : 'Negatif' ?></strong></em>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <?php
                                                                    $labels = [
                                                                        '1' => 'Sangat Tidak Setuju',
                                                                        '2' => 'Tidak Setuju',
                                                                        '3' => 'Netral',
                                                                        '4' => 'Setuju',
                                                                        '5' => 'Sangat Setuju'
                                                                    ];
                                                                    foreach ($labels as $value => $label):
                                                                        $inputId = 'preview_' . $item['id_sus'] . '_' . $value;
                                                                    ?>
                                                                        <div class="form-check form-check-inline" style="margin-right: 15px;">
                                                                            <input class="form-check-input" type="radio"
                                                                                id="<?= $inputId ?>"
                                                                                disabled>
                                                                            <label class="form-check-label" for="<?= $inputId ?>">
                                                                                <?= $value ?> (<?= $label ?>)
                                                                            </label>
                                                                        </div>
                                                                    <?php endforeach; ?>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ol>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end kuesioner -->

                                </div>
                                <!-- End Accordion -->

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Kolom kanan -->
            <section class="col-lg-4 col-md-12">
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



                        <div class="d-grid gap-2">
                            <!-- Update status -->
                            <div class="form-group">
                                <label for="statusSurvei">Status Survei</label>
                                <select name="status_survei" id="statusSurvei" class="form-control" data-id="<?= $id_survei ?>">
                                    <option value="draft" <?= $dataSurvei['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
                                    <option value="published" <?= $dataSurvei['status'] == 'published' ? 'selected' : '' ?>>Published</option>
                                    <option value="closed" <?= $dataSurvei['status'] == 'closed' ? 'selected' : '' ?>>Closed</option>
                                </select>
                            </div>

                            <!-- Pengaturan -->
                            <a href="?page=lihat-hasil-survei&id_survei=<?= $id_survei; ?>" class="btn btn-info btn-block mb-2"><i class="fas fa-eye"></i> Lihat Hasil</a>
                            <a href="?page=form-edit-survei&id_survei=<?= $id_survei; ?>" class="btn btn-warning btn-block mb-2"><i class="fas fa-edit"></i> Edit Survei</a>

                            <a href="#" class="btn btn-danger btn-block" onclick="hapusSurveiButton()">
                                <i class="fas fa-trash"></i> Hapus Survei
                            </a>

                        </div>
                    </div>
                </div>

                <!-- Bagikan Survei -->
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Bagikan Survei</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div id="bagikanSurvei" class="form-group">
                                <?php if (trim($dataSurvei['status']) === 'published') : ?>
                                    <label class="form-label">Link Survei</label>
                                    <div class="input-group mb-3">
                                        <!-- /btn-group -->
                                        <input type="text" class="form-control" id="surveyLink" value="https://survei-usability-testing.trpl7b.my.id/index.php?id=<?= $id_survei ?>" readonly>
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-success" onclick="copyLink()"><i class="fas fa-copy"></i></button>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <label class="form-label">Link Survei</label>
                                    <div class="input-group mb-3">
                                        <!-- /btn-group -->
                                        <input type="text" class="form-control" id="surveyLink" value="Survei Belum Bisa dibagikan" readonly>
                                        <div class="input-group-prepend">
                                            <button type="button" class="btn btn-success"><i class="fas fa-copy"></i></button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!--  Responden Baru -->
                <div class="card card-outline card-lightblue">
                    <div class="card-body text-center">
                        <p class="mb-2">Data Responden</p>
                        <a href="?page=list-responden&id_survei=<?= $id_survei; ?>" class="btn bg-lightblue"><i class="fas fa-user-plus"></i> Data Responden</a>
                    </div>
                </div>
            </section>
        </div>


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    // Fungsi hapus survei
    function hapusSurveiButton() {
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
                window.location.href = "?page=delete-survei&id_survei=<?= $id_survei ?>&id_login=<?= $id_login; ?>";
            }
        });
    }

    // fungsi hapus task skenario
    function hapusTaskSkenarioButton(id_task) {
        Swal.fire({
            title: 'Yakin ingin menghapus task skenario?',
            text: "Semua data yang berkaitan dengan task skenario ini akan ikut terhapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke halaman PHP yang akan handle penghapusan ?page=form-edit-task-skenario
                window.location.href = "?page=delete-task-skenario&id_survei=<?= $id_survei; ?>&id_task=" + id_task;
            }
        });
    }

    // fungsi jika pilih status survei dan ambil data kemudian muncul pesan serta ngirimkan data ke db
    document.getElementById('statusSurvei').addEventListener('change', function() {
        const newStatus = this.value;
        const idSurvei = this.getAttribute('data-id');

        // console.log(newStatus);
        // console.log(idSurvei);

        // fetch 
        fetch('https://survei-usability-testing.trpl7b.my.id/admin/survei/update_status_survei.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_survei=${encodeURIComponent(idSurvei)}&status=${encodeURIComponent(newStatus)}`
            })
            .then(response => {
                // Cek status HTTP dari response
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error(text);
                    });
                }
                return response.text();
            })
            .then(data => {
                console.log(data);
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Status survei telah diperbarui.',
                    timer: 1500,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan saat memperbarui status.'
                });
            });
    });

    // fungsi untuk mengganti form dan button bagikan survei
    document.getElementById("statusSurvei").addEventListener("change", function() {
        const status = this.value;
        const bagikanDiv = document.getElementById("bagikanSurvei");

        if (status === "published") {
            bagikanDiv.innerHTML = `
            <label for="linkSurvei">Bagikan Survei</label>
            <div class="input-group">
                <input type="text" class="form-control" id="surveyLink" value="https://survei-usability-testing.trpl7b.my.id/index.php?id=<?= $id_survei ?>" readonly>
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-success" onclick="copyLink()"><i class="fas fa-copy"></i></button>
                </div>
            </div>
        `;
        } else {
            bagikanDiv.innerHTML = `
            <label>Bagikan Survei</label>
            <div class="input-group mb-3">
                <!-- /btn-group -->
                <input type="text" class="form-control" id="surveyLink" value="Survei Belum Bisa dibagikan" readonly>
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-success"><i class="fas fa-copy"></i></button>
                </div>
            </div>
        `;
        }
    });


    // ini fungsi copy link 
    function copyLink() {
        const linkInput = document.getElementById('surveyLink');
        linkInput.select();
        document.execCommand('copy');
        linkInput.setSelectionRange(0, 99999); // Untuk mobile


        // Salin ke clipboard
        const successful = document.execCommand('copy');

        // Tampilkan notifikasi SweetAlert
        if (successful) {
            Swal.fire({
                icon: 'success',
                title: 'Tautan Disalin!',
                text: 'Link survey telah berhasil disalin ke clipboard.',
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Tidak dapat menyalin link. Silakan salin secara manual.',
            });
        }
    }
</script>