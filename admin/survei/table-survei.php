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
                                <li class="breadcrumb-item active">Survei Saya</li>
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
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table mr-1"></i>
                                        Survei yang Kamu Buat
                                    </h3>

                                </div>
                                <div class="card-body">
                                    <table id="survei1" class="table table-bordered table-striped">
                                        <?php

                                        $sqlSurveiDibuat = "
                                        SELECT 
                                            s.id,
                                            s.title,
                                            s.description,
                                            s.status,
                                            s.created_at
                                        FROM 
                                            surveys s
                                        JOIN 
                                            v_akun_users v ON s.id_login = v.id_login
                                        WHERE 
                                            v.id_login = ?
                                        ORDER BY 
                                            s.created_at DESC
                                        ";

                                        $stmtSurveiDibuat = $conn->prepare($sqlSurveiDibuat);
                                        $stmtSurveiDibuat->bind_param("i", $id_login);
                                        $stmtSurveiDibuat->execute();
                                        $resultSurveiDibuat = $stmtSurveiDibuat->get_result();

                                        ?>
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Survei</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 1;
                                            while ($row = $resultSurveiDibuat->fetch_assoc()) :

                                            ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= htmlspecialchars($row['title']); ?></td>
                                                    <td><?= htmlspecialchars(date("d-m-Y H:i", strtotime($row['created_at']))); ?></td>
                                                    <td>
                                                        <a href="?page=form-edit-survei&id_survei=<?= $row['id']; ?>" class="btn btn-warning"><i class="fas fa-edit"></i> Ubah</a>
                                                        <a href="?page=detail-survei&id_survei=<?= $row['id']; ?>" class="btn btn-info"><i class="fas fa-eye"></i> Lihat Detail</a>
                                                    </td>
                                                </tr>

                                            <?php endwhile; ?>


                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Survei</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body-->
                            </div>
                            <!-- /.card -->

                        </section>
                        <!-- /.Left col -->
                    </div>
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->