<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    ?>
    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Admin dashboard for generating reports">
        <meta name="author" content="Car Rental Portal">
        <meta name="theme-color" content="#3e454c">

        <title>Admin Dashboard | Generate Report</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Bootstrap CSS (Updated version) -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.bootstrap4.min.css">
        <!-- Custom Style -->
        <link rel="stylesheet" href="css/style.css">

        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap">

        <style>
            .table-wrapper {
                margin: 20px 0;
                background-color: #ffffff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .dataTables_length,
            .dataTables_filter {
                margin-bottom: 20px;
            }

            .dt-buttons .btn {
                margin-right: 5px;
                font-size: 14px;
                padding: 6px 12px;
            }

            /* Alternate row coloring */
            table.dataTable tbody tr:nth-child(odd) {
                background-color: #f9f9f9;
            }

            table.dataTable tbody tr:hover {
                background-color: #f1f1f1;
            }
        </style>
    </head>

    <body>
        <?php include('includes/header.php'); ?>

        <div class="ts-main-content">
            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="page-title">Generate Report</h2>
                            <div class="table-wrapper">
                                <table id="example" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User Email</th>
                                            <th>Vehicle ID</th>
                                            <th>Vehicle Name</th>
                                            <th>From Date</th>
                                            <th>To Date</th>
                                            <th>Status</th>
                                            <th>Type of Payment</th>
                                            <th>Booking Number</th>
                                            <th>Rating</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern jQuery and Bootstrap Bundle -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <!-- DataTables Buttons JS -->
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#example').DataTable({
                    "processing": true,
                    "ajax": "data.php",
                    "pageLength": 10,
                    "dom": 'lBfrtip',
                    "buttons": [
                        {
                            extend: 'collection',
                            text: '<i class="fa fa-download"></i> Export',
                            className: 'btn btn-secondary',
                            buttons: [
                                {
                                    extend: 'copyHtml5',
                                    text: '<i class="fa fa-copy"></i> Copy',
                                    titleAttr: 'Copy'
                                },
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                                    titleAttr: 'Export to Excel'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fa fa-print"></i> Print',
                                    titleAttr: 'Print'
                                }
                            ]
                        }
                    ]
                });
            });
        </script>
    </body>

    </html>
<?php } ?>