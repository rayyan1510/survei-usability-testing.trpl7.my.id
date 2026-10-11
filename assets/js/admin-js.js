  const footerYear = document.querySelectorAll(".tahun");
  footerYear.forEach(copyright => {
    copyright.innerHTML = new Date().getFullYear();
  });


$(document).ready(function () {

  // Number of error tabel ("Aspek efektivitas")
    $('#numberoferrors').DataTable({
        scrollX: true,
        scrollY: '300px',
        scrollCollapse: true,
        searching: true,
        paging: true,
        ordering: true,
        info: true,
        autoWidth: false,   // agar header tetap di atas saat scroll
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      }).buttons().container().appendTo('#numberoferrors_wrapper .col-md-6:eq(0)');
  
  // Success Rate tabel ("Aspek efektivitas")
      $('#successrate').DataTable({
        scrollX: true,
        scrollY: '300px',
        scrollCollapse: true,
        searching: true,
        paging: true,
        ordering: true,
        info: true,
        autoWidth: false,   // agar header tetap di atas saat scroll
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    }).buttons().container().appendTo('#successrate_wrapper .col-md-6:eq(0)');
  
    // efficiency tabel ("aspek efisiensi TBE")
      $('#efficiency').DataTable({
      "paging": true,
      "lengthChange": true,
      "scrollX": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      }).buttons().container().appendTo('#efficiency_wrapper .col-md-6:eq(0)');
  
  // Satisfaction tabel ("kepuasan")
  $('#kepuasan1').DataTable({
    dom: 'Bfrtip',
            buttons: [{
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn-success',
                    title: 'Data Kepuasan Pengguna',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            footer: function(data, row, column, node) {
                                // Hanya ambil baris pertama footer
                                return $(node).closest('table').find('tfoot tr:first th:last').text();
                            }
                        }
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row:last c', sheet).attr('s', '20'); // Style untuk footer
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn-info',
                    title: 'Data Kepuasan Pengguna',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            footer: function(data, row, column, node) {
                                return $(node).closest('table').find('tfoot tr:first th:last').text();
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn-danger',
                    title: 'Data Kepuasan Pengguna',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            footer: function(data, row, column, node) {
                                return $(node).closest('table').find('tfoot tr:first th:last').text();
                            }
                        }
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.content[1].table.widths =
                            Array($('#kepuasan1 thead tr:first th').length).fill('auto');

                        // Footer khusus untuk PDF
                        doc.footer = function(currentPage, pageCount) {
                            return {
                                text: 'Halaman ' + currentPage.toString() + ' dari ' + pageCount,
                                alignment: 'center',
                                fontSize: 8,
                                margin: [0, 10, 0, 0]
                            };
                        };
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Cetak',
                    className: 'btn-primary',
                    title: 'Data Kepuasan Pengguna',
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false,
                        format: {
                            footer: function(data, row, column, node) {
                                return $(node).closest('table').find('tfoot tr:first th:last').text();
                            }
                        }
                    },
                    customize: function(win) {
                        $(win.document.body).find('h1').css('text-align', 'center');
                        $(win.document.body).css('font-size', '10pt');

                        // Atur margin dan ukuran untuk cetakan
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit')
                            .css('width', '100%');

                        // Footer khusus untuk cetakan
                        $(win.document.body).append(
                            '<div style="text-align:center;font-size:8pt;margin-top:20px;">' +
                            'Dicetak pada: ' + new Date().toLocaleString() + '</div>'
                        );
                    }
                }
            ],

    "paging": true,
    "lengthChange": true,
    "scrollX": true,
    "searching": true,
    "ordering": true,
    "info": false,
    "autoWidth": false,   // agar header tetap di atas saat scroll
    "responsive": true,
  });

  // Satisfaction tabel ("kepuasan")
  // $ ('#kepuasan1').DataTable({
  //     paging: true,
  //     lengthChange: false,
  //     scrollX: true,
  //     searching: true,
  //     ordering: true,
  //     info: true,
  //     responsive: true,
  //     "autoWidth": false ,  // agar header tetap di atas saat scroll
      
  // });

});


$(function () {
    // Summernote
    $('#summernote').summernote({
      placeholder: 'Silahkan masukkan deskripsi survei anda',
      height: 120,
    });
  
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');


    //  $('#tabelresponden').DataTable({
    //   "paging": true,
    //   "lengthChange": true,
    //   "searching": true,
    //   "ordering": true,
    //   "info": true,
    //   "autoWidth": true,
    //   "responsive": true,
    //   "stateSave": true,
    //   // data: [], // inisialisasi kosong dulu
    //   // columns: [
    //   //     { data: 'no' },
    //   //     { data: 'nama_responden' },
    //   //     { data: 'waktu' },
    //   //     { data: 'status' },
    //   //     { data: 'aksi' }
    //   // ]
  // });


  // Deteksi apakah halaman berisi tabel responden 
  if ($('#tabelresponden').length) {
    const urlParams = new URLSearchParams(window.location.search);
    const idSurvei = urlParams.get('id_survei');

    let tabelResponden = $('#tabelresponden').DataTable({
      ajax: {
            url: `https://survei-usability-testing.trpl7b.my.id/admin/survei/get_responden.php?id_survei=${idSurvei}`,
        dataSrc: 'responden',
      },
      columns: [
        {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'nama_responden' },
            { data: 'waktu' },
            { data: 'status' },
            {
                data: null,
                render: function (data, type, row) {
                    if (row.konfirmasi === 1) {
                        return `<a href="?page=detail-responden&id_responden=${row.id_responden}&id_survei=${idSurvei}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>`;
                    } else {
                        return `<a href="?page=tanggapi-responden&id_responden=${row.id_responden}&id_survei=${idSurvei}" class="btn btn-success btn-sm">
                                    <i class="fas fa-check-circle"></i> Konfirmasi
                                </a>`;
                    }
                }
            }
      ],
      responsive: true,
        autoWidth: false,
        language: {
            url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
        }
    });

     // Opsional: reload otomatis tiap 5 detik tanpa reset halaman
    setInterval(() => {
        tabelResponden.ajax.reload(null, false);
    }, 5000);
  }

  

    $('#survei1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
  
    
    // $('#satisfaction').DataTable({
    //   "scrollX": true,
    //   "autoWidth": true,
    //   "responsive": true,
    //   "paging": true,
    //   "searching": true,
    //   "ordering": true,
    //   "info": true,
    //   "language": {
    //     search: "Cari:",
    //     lengthMenu: "Tampilkan _MENU_ entri",
    //     info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    //     infoEmpty: "Tidak ada data tersedia",
    //     zeroRecords: "Tidak ditemukan data yang cocok",
    //     paginate: {
    //       previous: "Sebelumnya",
    //       next: "Berikutnya"
    //     }
    //   }
    // });



    // $('#successrate1').DataTable({
    //   "paging": true,
    //   "lengthChange": true,
    //   "searching": true,
    //   "ordering": true,
    //   "scrollX": true,
    //   "scrollY": 200,
    //   // "info": true,
    //   "autoWidth": true,
    //   "responsive": true,
    // });

    $('#summernoteEditSurvei').summernote({
        placeholder: 'Silahkan masukkan deskripsi survei anda',
        height: 120,
    });
    
    // ===========================================================
    // Inisialisasi Summernote pertama
    $('.summernoteTaskSkenario').summernote({
      placeholder: 'Silahkan masukkan deskripsi teks skenario anda',
      height: 120
    });

    // Fungsi untuk update nomor pada setiap card
    function updateTaskNumbers() {
      $('#containerForm .task-card').each(function (index) {
        $(this).find('.card-title').text('Task Skenario ' + (index + 1));
      });
    }

     // Fungsi tambah form baru
    $('#tambahForm').click(function () {
      const newCard = `
        <div class="card card-outline card-primary task-card mb-3">
          <div class="card-header">
            <h3 class="card-title">Task Skenario</h3>
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
      `;

      $('#containerForm').append(newCard);

      // Inisialisasi Summernote untuk textarea baru
      $('#containerForm .summernoteTaskSkenario').each(function () {
        if (!$(this).next().hasClass('note-editor')) {
          $(this).summernote({
            placeholder: 'Silahkan masukkan deskripsi teks skenario anda',
            height: 120
          });
        }
      });

      updateTaskNumbers();
    });

    // Hapus card dan update nomor
    $('#containerForm').on('click', '.btn-hapus-card', function () {
      $(this).closest('.card').remove();
      updateTaskNumbers();
    });

    // Collapse card body
    $('#containerForm').on('click', '.btn-collapse', function () {
      $(this).closest('.card').find('.card-body').slideToggle();
      $(this).find('i').toggleClass('fa-minus fa-plus');
    });


     // ===========================================================
    // Inisialisasi summernoteTaskSkenarioCatatan
    $('.summernoteTaskSkenarioCatatan').summernote({
      placeholder: 'Silahkan masukkan catatan Observasi Anda',
      height: 120
    });


  });


// Dumpy
//  function loadResponden() {
//         fetch(`survei/get_responden.php?id_survei=${idSurvei}`)
//             .then(response => response.json())
//             .then(data => {
//                 if (data.error) {
//                     console.error(data.error);
//                     return;
//                 }

//                 // Update tabel responden
//                 // const tbody = document.getElementById('tbody-responden');
//                 // tbody.innerHTML = ''; // kosongkan dulu



//                 let no = 1;
//                 const newRow = data.responden.forEach(res => {
//                     // Buat tombol aksi konfirmasi/detail
//                     let aksiBtn = '';
//                     if (res.konfirmasi !== 1) {
//                         aksiBtn = `<a href="?page=tanggapi-responden&id_responden=${res.id_responden}&id_survei=${idSurvei}" class="btn btn-success btn-sm mr-2">
//                                     <i class="fas fa-check-circle pr-1"></i>Konfirmasi
//                                 </a>`;
//                     } else {
//                         aksiBtn = `<a href="?page=detail-responden&id=${res.id_responden}" class="btn btn-info btn-sm mr-2">
//                                     <i class="fas fa-eye pr-1"></i>Detail
//                                 </a>`;
//                     }

//                     return [
//                         no++,
//                         res.nama_responden,
//                         res.waktu,
//                         res.status,
//                         `<div class="d-flex">${aksiBtn}</div>`
//                     ];
//                 });

//                 // Cek apakah DataTable sudah diinisialisasi
//                 if (!$.fn.DataTable.isDataTable('#tabelresponden')) {
//                     tableResponden = $('#tabelresponden').DataTable({
//                         data: newRows,
//                         columns: [{
//                                 title: "No"
//                             },
//                             {
//                                 title: "Nama Responden"
//                             },
//                             {
//                                 title: "Waktu"
//                             },
//                             {
//                                 title: "Status"
//                             },
//                             {
//                                 title: "Aksi"
//                             }
//                         ],
//                         stateSave: true,
//                         paging: true,
//                         lengthChange: true,
//                         searching: true,
//                         ordering: true,
//                         autoWidth: true,
//                         responsive: true
//                     });
//                 } else {
//                     tabelResponden.clear().rows.add(rows).draw();
//                 }

//                 // Update info survei kanan
//                 document.getElementById('total-responden').textContent = data.total;
//                 document.getElementById('sudah-konfirmasi').textContent = data.terkonfirmasi;
//                 document.getElementById('belum-konfirmasi').textContent = data.belumKonfirmasi;
//             })
//             .catch(err => console.error('Error load responden:', err));
//     }

//     // Load pertama kali
//     loadResponden();

//     // Refresh setiap 3 detik
//     setInterval(loadResponden, 3000);


// function loadResponden() {
//   const idSurvei = <?= json_encode($id_survei) ?>
//     fetch(`survei/get_responden.php?id_survei=${idSurvei}`)
//       .then(response => response.json())
//       .then(data => {
//         if (data.error) {
//           console.error(data.error);
//           return;
//         }

//         // Update tabel responden
//         const tbody = document.getElementById('tbody-responden');
//         tbody.innerHTML = ''; // kosongkan dulu

//         let no = 1;
//         data.responden.forEach(res => {
//           // Buat tombol aksi konfirmasi/detail
//           let aksiBtn = '';
//           if (res.konfirmasi !== 1) {
//             aksiBtn = <a href="?page=tanggapi-responden&id_responden=${res.id_responden}&id_survei=${idSurvei}" class="btn btn-success btn-sm mr-2">
//               <i class="fas fa-check-circle pr-1"></i>Konfirmasi
//             </a>;
//           } else {
//             aksiBtn = <a href="?page=detail-responden&id=${res.id_responden}" class="btn btn-info btn-sm mr-2">
//               <i class="fas fa-eye pr-1"></i>Detail
//             </a>;
//           }

//           tbody.innerHTML +=
//             <tr>
//               <td>${no++}</td>
//               <td>${res.nama_responden}</td>
//               <td>${res.waktu}</td>
//               <td>${res.status}</td>
//               <td><div class="d-flex">${aksiBtn}</div></td>
//             </tr>
//             ;
//         });

//         // Update info survei kanan
//         document.getElementById('total-responden').textContent = data.total;
//         document.getElementById('sudah-konfirmasi').textContent = data.terkonfirmasi;
//         document.getElementById('belum-konfirmasi').textContent = data.belumKonfirmasi;
//       })
//       .catch(err => console.error('Error load responden:', err))
//     }

//     // Load pertama kali
//     loadResponden();

//     // Refresh setiap 3 detik
//     setInterval(loadResponden, 3000);