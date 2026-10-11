<?php

// session_start();


// cek sesi login
if (!isset($_SESSION['id_login'])) {
    header("Location: ../../login.php");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $judul = $_POST['namaSurvei'];
    $deskripsi = $_POST['deskripsi'];
    $status = 'draft'; // default status
    $id_login = $_SESSION['id_login'];


    $query = "INSERT INTO surveys (title, description, status, id_login, created_at) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $judul, $deskripsi, $status, $id_login);


    if ($stmt->execute()) {
        // if ($cek == true) {

        $id_survei_baru = $stmt->insert_id; // ambil ID survei yang baru dibuat


        // redirect ke halaman pembuatan skenario, kirimkan ID survei via GET &id_survei=$id_survei_baru
        // echo "
        //         <script>
        //             Swal.fire({
        //                 title: 'Berhasil!',
        //                 text: 'Survei berhasil ditambahkan.',
        //                 icon: 'success',
        //                 confirmButtonText: 'Lanjut'
        //             }).then((result) => {
        //                 if (result.isConfirmed) {
        //                     window.location.href = 'index.php?page=task-skenario';
        //                 }
        //             });
        //         </script>
        //         ";
        // redirect ke halaman pembuatan skenario, kirimkan ID survei via GET
        // header("Location: index.php?page=buat-survey");
        // header("Location: index.php?page=task-skenario");
        echo "
            <script>
               alert('Survei Berhasil Dibuat'); window.location.href = '?page=detail-survei&id_survei=$id_survei_baru';
            </script>
            ";
        // exit();
    } else {
        $error = $stmt->error; //" . addslashes($error) . "

        // echo "
        //     <script>
        //         Swal.fire({
        //             title: 'Gagal!',
        //             text: 'Survei gagal ditambahkan: ',
        //             icon: 'error',
        //             confirmButtonText: 'OK'
        //         }).then((result) => {
        //             window.location.href = '?page=buat-survey';
        //         });
        //     </script>
        //     ";

        echo "
            <script>
               alert('gagal, $error'); window.location.href = 'index.php?page=buat-survey';
            </script>
            ";
    }

    $stmt->close();
}
