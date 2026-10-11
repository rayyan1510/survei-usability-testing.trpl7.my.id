<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    # code...
    $id_survei = isset($_POST['id_survei']) ? (int) $_POST['id_survei'] : 0;
    if ($id_survei <= 0) {

        echo "<script>alert('ID survei tidak valid!'); window.location.href = '?page=form-sus&id_survei=$id_survei';</script>";
        die("ID survei tidak valid.");
    }


    $sus_statements = [
        1 => ["Saya rasa saya akan menggunakan sistem ini lebih sering.", 1],
        2 => ["Saya merasa sistem ini terlalu kompleks.", 0],
        3 => ["Saya rasa sistem ini mudah digunakan.", 1],
        4 => ["Saya rasa saya memerlukan bantuan teknisi saat menggunakan sistem ini.", 0],
        5 => ["Saya menilai fitu-fitur dalam sistem ini terintegrasi dengan baik.", 1],
        6 => ["Saya pikir terlalu banyak ketidakkonsistenan dalam sistem ini.", 0],
        7 => ["Saya membayangkan bahwa kebanyakan orang akan cepat beradaptasi dengan sistem ini.", 1],
        8 => ["Saya rasa sistem ini sangat sulit digunakan.", 0],
        9 => ["Saya merasa sangat percaya diri saat menggunakan sistem ini.", 1],
        10 => ["Saya perlu mempelajari banyak hal sebelum dapat menggunakan sistem ini.", 0],
    ];

    // Siapkan query
    $stmt = $conn->prepare("INSERT INTO survei_sus (nomor_pernyataan, pernyataan, is_positif, id_survei, created_at) VALUES (?, ?, ?, ?, NOW())");

    if (!$stmt) {
        echo "<script>alert('Gagal menyiapkan statement SQL.'); window.history.back();</script>";
        exit;
    }

    $sukses = true;

    foreach ($sus_statements as $nomor => [$pernyataan, $is_positif]) {
        $stmt->bind_param("isii", $nomor, $pernyataan, $is_positif, $id_survei);
        if (!$stmt->execute()) {
            $sukses = false;
            break;
        }
    }

    $stmt->close();

    if ($sukses) {
        echo "<script>alert('Pernyataan SUS berhasil disimpan!');
            window.location.href='?page=detail-survei&id_survei=$id_survei';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan pernyataan SUS.'); window.location.href = '?page=form-sus&id_survei=$id_survei';</script>";
    }

    exit;
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
                    <li class="breadcrumb-item"><a href="#">Buat Survei</a></li>
                    <li class="breadcrumb-item active">Kuesioner SUS</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <form action="" method="post">
            <div class="row">
                <!-- Kolom kiri -->
                <section class="col-md-12">
                    <div class="card card-outline card-primary task-card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Form SUS</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>


                        <div class="card-body">
                            <!-- Petunjuk -->
                            <div class="alert alert-info">
                                <strong>Petunjuk:</strong> Pernyataan di bawah ini merupakan bagian dari kuesioner SUS.
                                Setiap pernyataan memiliki skala dari <strong>1 (Sangat Tidak Setuju)</strong> hingga <strong>5 (Sangat Setuju)</strong>.
                                Form ini hanya untuk ditampilkan, tidak dapat diisi.
                            </div>

                            <input type="hidden" name="id_survei" value="<?= $_GET['id_survei']; ?>">


                            <?php
                            $pertanyaan = [
                                "Saya rasa saya akan menggunakan sistem ini lebih sering.",
                                "Saya merasa sistem ini terlalu kompleks.",
                                "Saya rasa sistem ini mudah digunakan.",
                                "Saya rasa saya memerlukan bantuan teknisi saat menggunakan sistem ini.",
                                "Saya menilai fitu-fitur dalam sistem ini terintegrasi dengan baik.",
                                "Saya pikir terlalu banyak ketidakkonsistenan dalam sistem ini.",
                                "Saya membayangkan bahwa kebanyakan orang akan cepat beradaptasi dengan sistem ini.",
                                "Saya rasa sistem ini sangat sulit digunakan.",
                                "Saya merasa sangat percaya diri saat menggunakan sistem ini.",
                                "Saya perlu mempelajari banyak hal sebelum dapat menggunakan sistem ini."
                            ];

                            $skalaLikert = [
                                1 => 'Sangat Tidak Setuju',
                                2 => 'Tidak Setuju',
                                3 => 'Netral',
                                4 => 'Setuju',
                                5 => 'Sangat Setuju'
                            ];

                            ?>
                            <?php foreach ($pertanyaan as $i => $text): ?>
                                <div class="form-group border-bottom pb-2 mb-0">
                                    <label><strong><?= ($i + 1) ?>.</strong> <?= $text ?></label>
                                    <!-- <input class="form-check-input" type="hidden" name="pernyataanSUS[]" id="pernyataanSUS" value="<?= $text ?>"> -->
                                    <div class="form-row mt-2">
                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                            <div class="col text-center">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="sus[<?= $i ?>]" id="sus<?= $i ?>_<?= $j ?>" value="" disabled>
                                                    <label class="form-check-label" for="sus<?= $i ?>_<?= $j ?>"><?= $j ?> - <?= $skalaLikert[$j] ?></label>
                                                </div>
                                            </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- card-footer -->
                        <div class="card-footer">
                            <a href="#" class="btn btn-secondary" onclick="navigasibalik()"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-save"></i> Submit</button>
                        </div>
                    </div>
                </section>
            </div>
        </form>

    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
    function navigasibalik() {
        window.history.back();
    }
</script>