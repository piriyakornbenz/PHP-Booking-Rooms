<?php

    session_start();
    require('./components/count.php');

    if (isset($_SESSION['login'])) {
        $user_id = $_SESSION['login'];
    }else {
        header('location: login.php');
        exit();
    }

    function build_calendar($month, $year, $room)
    {
        require('./config.php');
        
        // create option rooms
        $stmt = $conn->prepare("SELECT * FROM rooms");
        $rooms = "";

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $rooms .= "<option value=".$row['id'].">".$row['room_name']."</option>";
                }
            }
        }

        $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];

        $prevMonth = $month - 1;
        $nextMonth = $month + 1;
        $prevYear = $year;
        $nextYear = $year;

        if ($prevMonth < 1) {
            $prevMonth = 12;
            $prevYear--;
        }

        if ($nextMonth > 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        $calendar = "<center class='mt-4'><h2>$monthName $year</h2>";
        $calendar .= "<div>";
        $calendar .= "<a class='btn btn-secondary' href='?month=$prevMonth&year=$prevYear'>< Prev Month</a> ";
        $calendar .= "<a class='btn btn-primary' href='?month=" . date('m') . "&year=" . date('Y') . "'>Current Month</a> ";
        $calendar .= "<a class='btn btn-secondary' href='?month=$nextMonth&year=$nextYear'>Next Month ></a>";
        $calendar .= "</div><br>
            <form id='room_select_form'>
                <div class='row d-flex justify-content-start mb-4'>
                    <div class='col-md-3 text-start'>
                        <label class='form-label'>Select room</label>
                        <select class='form-select bg-light shadow-sm' id='room_select' name='room'>
                            '$rooms'
                        </select>
                        <input type='hidden' name='month' value='$month'>
                        <input type='hidden' name='year' value='$year'>
                    </div>
                </div>
            </form>
        ";
        $calendar .= "<table class='table table-bordered'>";
        $calendar .= "<tr>";

        foreach ($daysOfWeek as $day) {
            $calendar .= "<th class='header text-center'>$day</th>";
        }

        $calendar .= "</tr><tr>";

        if ($dayOfWeek > 0) {
            for ($k = 0; $k < $dayOfWeek; $k++) {
                $calendar .= "<td class='empty'></td>";
            }
        }

        $currentDay = 1;

        $month = str_pad($month, 2, "0", STR_PAD_LEFT);

        while ($currentDay <= $numberDays) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }

            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $today = $date == date('Y-m-d') ? 'bg-info' : '';

            $dayName = strtolower(date('l', strtotime($date)));
            
            // show button Holiday, N/A, All Booked and Book
            if ($dayName == 'saturday' || $dayName == 'sunday') {
                $calendar .= "<td class='$today text-center'><h4>$currentDayRel</h4><button class='btn btn-secondary btn-sm' disabled>Holiday</button></td>";
            } elseif ($date < date('Y-m-d')) {
                $calendar .= "<td class='$today text-center'><h4>$currentDayRel</h4><button class='btn btn-secondary btn-sm' disabled>N/A</button></td>";
            } else {
                $totalbooking = checkSlot($conn, $date, $room);
                $slotCount = slotCount();
                $available = $slotCount-$totalbooking;

                if ($totalbooking == $slotCount) {
                    $calendar .= "<td class='$today text-center'><h4>$currentDayRel</h4><a href='booking_admin.php?date=" . $date . "' class='btn btn-danger btn-sm'>All Booked</a></td>";
                    
                }
                $calendar .= "<td class='$today text-center'><h4>$currentDayRel</h4><a href='booking_admin.php?date=" . $date . "&room=" . $room . "' class='btn btn-success btn-sm'>Book</a><br class='d-block d-lg-none'> <small class='text-success'><i>$available left</i></small></td>";
            }

            $currentDay++;
            $dayOfWeek++;
        }

        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for ($l = 0; $l < $remainingDays; $l++) {
                $calendar .= "<td class='empty'></td>";
            }
        }

        $calendar .= "</tr>";
        $calendar .= "</table></center>";

        return $calendar;
    }

    // count booking by date and room_id
    function checkSlot($conn, $date, $room) {
        $stmt = $conn->prepare("SELECT COUNT(*) as totalbooking FROM bookings WHERE date = :date AND room_id = :room_id");
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':room_id', $room);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['totalbooking'];
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid">
        <div class="row vh-100">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="d-flex flex-column h-100 justify-content-between">
                    <div class="position-sticky pt-3">
                        <div class="pt-2 px-2 text-center">
                            <a href="/" class="text-white mt-2 text-decoration-none">
                                <i class="fa-solid fa-database me-2"></i>
                                <span class="fs-5">Dashboard</span>
                            </a>
                            <hr class="text-white">
                        </div>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="dashboard.php" class="nav-link text-white active">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <span><small>Calendar</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="mybooking_admin.php" class="nav-link text-white">
                                    <i class="fa-solid fa-calendar-check me-2"></i>
                                    <span><small>My Booking</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="employee.php" class="nav-link text-white">
                                    <i class="fa-solid fa-users me-2"></i>
                                    <span><small>Employee</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="rooms.php" class="nav-link text-white">
                                    <i class="fa-solid fa-shop me-2"></i>
                                    <span><small>Rooms</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="setting.php" class="nav-link text-white">
                                    <i class="fa-solid fa-gear me-2"></i>
                                    <span><small>Setting</small></span>
                                </a>
                            </li>
                        </ul>
                        
                    </div>
                    <div class="dropdown dropdown-center mb-4">
                        <hr class="text-white">
                        <a class="text-white text-decoration-none d-flex justify-content-center align-items-center" href="logout.php" onclick="return confirm('Are you sure')">
                            <i class="fa-solid fa-right-from-bracket me-2 fs-6"></i>
                            <h6 class="mb-0">Log out</h6>
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <nav aria-label="breadcrumb" class="bg-light mt-4 rounded">
                    <ol class="breadcrumb p-2">
                        <li class="breadcrumb-item active">Calendar</li>
                    </ol>
                </nav>
                <div class="pt-2 pb-2 mb-2 border-bottom">
                    <h2 class="text-center">Calendar</h2>
                    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="container shadow rounded">
                    <div class="row">
                        <div class="col-md-12">
                            <?php if (isset($_SESSION['success'])) { ?>
                                <div class="alert alert-success mt-2">
                                    <?php
                                    echo $_SESSION['success'];
                                    unset($_SESSION['success']);
                                    ?>
                                </div>
                            <?php } ?>
                            <?php
                            $dateComponents = getdate();
                            if (isset($_GET['month']) && isset($_GET['year'])) {
                                $month = $_GET['month'];
                                $year = $_GET['year'];
                            } else {
                                $month = $dateComponents['mon'];
                                $year = $dateComponents['year'];
                            }

                            if (isset($_GET['room'])) {
                                $room = $_GET['room'];
                            }else {
                                $room = 1;
                            }

                            echo build_calendar($month, $year, $room);
                            ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $("#room_select").change(function() {
            $("#room_select_form").submit();
        })

        $("#room_select option[value='<?php echo $room; ?>']").attr('selected', 'selected')
    </script>

</body>
</html>