<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    // Fetch bookings data from the database
    $sql = "SELECT tblbooking.id, tblusers.FullName, tblvehicles.VehiclesTitle, tblbooking.FromDate, tblbooking.ToDate, tblbooking.Status FROM tblbooking JOIN tblusers ON tblusers.EmailId=tblbooking.userEmail JOIN tblvehicles ON tblvehicles.id=tblbooking.VehicleId";
    $query = $dbh->prepare($sql);
    $query->execute();
    $bookings = $query->fetchAll(PDO::FETCH_ASSOC);

    $booking_data = [];
    foreach ($bookings as $booking) {
        // Convert booking dates to required format for FullCalendar
        $booking_data[] = [
            'title' => $booking['FullName'] . ' - ' . $booking['VehiclesTitle'],
            'start' => $booking['FromDate'],
            'end' => $booking['ToDate'],
            'status' => $booking['Status'],
            'id' => $booking['id'],
        ];
    }

    // Pass booking data to JavaScript
    $booking_json = json_encode($booking_data);
    ?>

    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">

        <title>Car Rental Portal | Staff Manage Bookings</title>

        <!-- FullCalendar CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css">
        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Sandstone Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <!-- Admin Style -->
        <link rel="stylesheet" href="css/style.css">
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .modal-body {
                text-align: center;
            }
        </style>

    </head>


    <body>
        <?php include('includes/header.php'); ?>

        <div class="ts-main-content">

            <?php include('includes/leftbar.php'); ?>
            <div class="content-wrapper">
                <div class="container-fluid">


                    <h2 class="page-title">Manage Bookings - Calendar View</h2>

                    <!-- Calendar Display -->
                    <div id="calendar"></div>

                    <!-- Booking Details Modal -->
                    <div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModa
                        l           Label" aria-hidden="true">

                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingModalLabel">Booking Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p id="bookingDetails"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Loading Scripts -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <!-- FullCalendar JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>

        <script>
            $(document).ready(function () {
                var bookings = <?php echo $booking_json; ?>;

                // Initialize FullCalendar
                $('#calendar').fullCalendar({
                    events: bookings,
                    eventClick: function (event) {
                        // Show booking details in the modal
                        var status = event.status == 0 ? 'Not Confirmed' : event.status == 1 ? 'Confirmed' :
                            event.status == 3 ? 'On-Going' : event.status == 4 ? 'Done' : 'Rejected';
                        var details = `
                    <strong>Name:</strong> ${event.title} <br>
                    <strong>From Date:</strong> ${event.start.format('YYYY-MM-DD')} <br>
                    <strong>To Date:</strong> ${event.end.format('YYYY-MM-DD')} <br>
                    <strong>Status:</strong> ${status} <br>`;
                        $('#bookingDetails').html(details);
                        $('#bookingModal').modal('show');
                    },
                    defaultView: 'month', // Default view is monthly
                    header: {
                        left: 'prev,next today', // Previous, next, today buttons
                        center: 'title', // Show calendar title in the center
                        right: 'day,agendaWeek,month' // Buttons for daily, weekly, and monthly views
                    },
                    views: {
                        day: {
                            titleFormat: 'MMMM D, YYYY', // Format for the day view title
                            columnFormat: 'dddd, MMMM D, YYYY' // Format for the day columns
                        },
                        agendaWeek: {
                            titleFormat: 'MMMM D, YYYY', // Format for the week view title
                            columnFormat: 'ddd, MMMM D' // Format for the week columns
                        },
                        month: {
                            titleFormat: 'MMMM YYYY' // Format for the month view title
                        }
                    },
                    allDayDefault: true,   // Ensure all-day events are displayed properly
                    editable: false,       // Disable event dragging if you want it to be read-only
                    droppable: false       // Disable dragging events from outside if not needed
                });
            });


        </script>

    </body>

    </html>
<?php } ?>