<?php


// $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// var_dump($_GET);
// var_dump($page);

if (isset($_GET['page'])) {
    $page = @$_GET['page'];

    // cek data dari url berupa parameter page
    switch ($page) {
        // Index Admin
        case 'dashboard':
            include './dashboard/dashboard.php';
            break;

        // ============================================
        //  Survei
        case 'buat-survey':
            include 'survei/form-tambah-survei.php';
            break;

        case 'proses-simpan-survei':
            include 'survei/proses-simpan-survei.php';
            break;

        case 'form-edit-survei':
            include 'survei/form-edit-survei.php';
            break;

        case 'proses-edit-survei':
            include 'survei/proses-edit-survei.php';
            break;

        case 'delete-survei':
            include './survei/delete-survei.php';
            break;


        // ================================================
        // task-skenario
        case 'task-skenario':
            include './survei/form-task-skenario.php';
            break;

        case 'proses-simpan-task':
            include './survei/proses-simpan-task.php';
            break;

        case 'form-edit-task-skenario':
            include 'survei/form-edit-task-skenario.php';
            break;

        case 'delete-task-skenario':
            include './survei/delete-task-skenario.php';
            break;

        // ======================================
        // Create Pernyataan Sus
        case 'form-sus':
            include './survei/form-sus.php';
            break;

        // ========================================
        // detail survei
        case 'detail-survei':
            include './survei/detail-survei.php';
            break;

        case 'lihat-hasil-survei':
            include './survei/hasil-survei.php';
            break;

        case 'tanggapi-responden':
            include './survei/task-skenario-catatan.php';
            break;

        case 'proses-simpan-task-catatan':
            include './survei/proses-simpan-task-catatan.php';
            break;

        case 'edit-tanggapi-responden':
            include './survei/edit-task-skenario-catatan.php';
            break;

        // ========================================
        // tabel responden
        case 'list-responden':
            include './survei/table-responden.php';
            break;

        //  detail responden
        case 'detail-responden':
            include './survei/detail-responden.php';
            break;


        // =======================================
        // list survei
        // case 'survei-saya':
        //     include './survei/table-survei.php';
        //     break;

        // =======================================
        // Faq
        case 'faq':
            include './faq/faq.php';
            break;


        // ======================================
        // cetak
        case 'cetak-responden-detail':
            include './export/cetak-responden.php';
            break;

            // default:
            //     # code...
            //     break;
    }
} else {
    include './dashboard/dashboard.php';
}
