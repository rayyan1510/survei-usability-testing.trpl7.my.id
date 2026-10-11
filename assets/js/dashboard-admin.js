// $(function () {
//     'use strict'

//     //-------------
//     // - PIE CHART -
//     //-------------
//     // Get context with jQuery - using jQuery's .get() method.
//     var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
//     var pieData = {
//         labels: [
//             'Chrome',
//             'IE',
//             'FireFox',
//             'Safari',
//             'Opera',
//             'Navigator'
//         ],
//         datasets: [
//             {
//                 data: [700, 500, 400, 600, 300, 100],
//                 backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
//             }
//         ]
//     }
//     var pieOptions = {
//         legend: {
//             display: false
//         }
//     }
//     // Create pie or douhnut chart
//     // You can switch between pie and douhnut using the method below.
//     // eslint-disable-next-line no-unused-vars
//     var pieChart = new Chart(pieChartCanvas, {
//         type: 'doughnut',
//         data: pieData,
//         options: pieOptions
//     })

//     //-----------------
//     // - END PIE CHART -
//     //-------------
// });

    var ctx = document.getElementById('Pekerjaan').getContext('2d');
    var pekerjaanPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pekerjaanLabels,
            datasets: [{
                data: pekerjaanData,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8',
                    '#6f42c1', '#20c997', '#e83e8c', '#fd7e14', '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            legend: {
                    display: false // ini menghilangkan legend
            }
        }
    });

    var ctx = document.getElementById('Umur').getContext('2d');
    var umurPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: umurLabels,
            datasets: [{
                data: umurData,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545', '#17a2b8',
                    '#6f42c1', '#20c997', '#e83e8c', '#fd7e14', '#6610f2'
                ]
            }]
        },
        options: {
            responsive: true,
            legend: {
                    display: false // ini menghilangkan legend
            }
        }
    });