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
                    <li class="breadcrumb-item active">Task Skenario</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <form action="?page=proses-simpan-task" method="post">
            <input type="hidden" name="id_survei" value="<?= $_GET['id_survei']; ?>">
            <div class="row">
                <!-- Kolom kiri -->
                <section class="col-md-8">
                    <div id="containerForm">
                        <!-- Card -->
                        <div class="card card-outline card-primary task-card mb-3">
                            <div class="card-header">
                                <h3 class="card-title">Task Skenario 1</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool btn-collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool btn-hapus-card" title="Hapus">
                                        <i class="fas fa-times text-danger"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Task Skenario</label>
                                    <input type="text" class="form-control" name="taskSkenario[]" placeholder="Silahkan Masukkan Judul" required>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi Teks Skenario</label>
                                    <textarea class="summernoteTaskSkenario" name="deskripsiTeksSkenario[]" rows="5" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Kolom kanan -->
                <section class="col-md-4">
                    <button type="button" id="tambahForm" class="btn btn-info btn-block mb-3">
                        <i class="fas fa-plus"></i> Tambah Task
                    </button>

                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Pengaturan</h3>
                        </div>
                        <div class="card-body">
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