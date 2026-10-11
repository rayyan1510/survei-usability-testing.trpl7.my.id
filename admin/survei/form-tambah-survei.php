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
                    <li class="breadcrumb-item active">Buat Survei</li>
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
            <section class="col-lg-12">
                <!-- card -->
                <div class="card card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            Buat Survei
                        </h3>

                    </div>
                    <form action="?page=proses-simpan-survei" method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="namaSurvei">Nama Survei</label>
                                <input type="text" class="form-control" name="namaSurvei" id="namaSurvei" placeholder="Silahkan Masukkan Judul" required>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Survei</label>
                                <textarea id="summernote" name="deskripsi" required></textarea>
                            </div>
                        </div>

                        <!-- /.card-body-->
                        <div class="card-footer">
                            <a href="index.php?page=dashboard" type="submit" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                            <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-save"></i> Submit</button>
                        </div>
                    </form>
                </div>
                <!-- /.card -->

            </section>
            <!-- /.Left col -->
        </div>
        <!-- /.row (main row) -->


    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->