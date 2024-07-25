<?php

    session_start();
    require('./config.php');

    if (isset($_SESSION['login'])) {
        $user_id = $_SESSION['login'];
    
        $stmt = $conn->prepare("SELECT * FROM employee WHERE id=:id");
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $user = $stmt->fetch();
    
    }else {
        header('location: login.php');
        exit();
    }

    $currentDate = date('Y-m-d');
    $currentTime = date('h:iA');
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
                                <span class="fs-5">Booking Room Web</span>
                            </a>
                            <hr class="text-white">
                        </div>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="index.php" class="nav-link text-white" aria-current="page">
                                    <i class="fa-solid fa-calendar-days me-2"></i>
                                    <span><small>Calendar</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="mybooking.php" class="nav-link text-white active">
                                    <i class="fa-solid fa-calendar-check me-2"></i>
                                    <span><small>My Booking</small></span>
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
                        <li class="breadcrumb-item"><a href="mybooking.php">My Booking</a></li>
                        <!-- <li class="breadcrumb-item active"><a href="" class="text-decoration-none text-secondary">Booking</a></li> -->
                    </ol>
                </nav>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h2>My Booking</h2>
                    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="container shadow rounded">
                    <div class="text-center mt-2">
                        <h1>My Booking</h1>
                    </div>
                    <hr>
                    <div class="row ">

                        <div class="col-md-12">
                            <?php if (isset($_SESSION['success'])) { ?>
                                <div class="alert alert-success">
                                    <?php
                                    echo $_SESSION['success'];
                                    unset($_SESSION['success']);
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="col-md-12">
                            <?php if (isset($_SESSION['error'])) { ?>
                                <div class="alert alert-danger">
                                    <?php
                                    echo $_SESSION['error'];
                                    unset($_SESSION['error']);
                                    ?>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="container">
                        <table class="table table-striped table-bordered table-hover shadow-sm">
                            <thead>
                            <tr class="text-center">
                                <th scope="col">Date</th>
                                <th scope="col">Timeslot</th>
                                <th scope="col">Heading</th>
                                <th scope="col">Room</th>
                                <th scope="col">Cancel</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php

                            $stmt = $conn->prepare("SELECT bookings.*, rooms.room_name FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = :user_id ORDER BY bookings.date DESC");
                            $stmt->bindParam(':user_id', $user_id);
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            
                            if(!empty($result)) {
                                foreach ($result as $row) {
                                    // timeslot 
                                    $timeslot = $row['timeslot'];
                                    list($timeslotStart, $timeslotEnd) = explode(' - ', $timeslot);
                                    $currentTime24 = date('H:iA', strtotime($currentTime));
                            ?>
                                
                            <tr class="text-center">
                                <td class="align-middle"><?= $row['date'] ?></td>
                                <td class="align-middle"><?= $row['timeslot'] ?></td>
                                <td class="align-middle"><?= $row['heading'] ?></td>
                                <td class="align-middle"><?= $row['room_name'] ?></td>
                                <?php if($row['date'] < $currentDate || ($row['date'] == $currentDate && $currentTime24 > $timeslotEnd)) { ?>
                                    <td class="align-middle"><a class="btn btn-secondary disabled">finished</a></td>
                                <?php }else { ?>
                                    <td class="align-middle"><a href="cancel.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to cancel?')">cancel</a></td>
                                <?php } ?>
                            </tr>
                            <?php } }else { ?>
                                <td colspan="5" class="text-center">No booking</td>
                            <?php } ?>
                            </tbody>
                        </table>
                        </div>

                    </div>
                </div>
            </main>
        </div>
    </div>


    <!-- modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><strong>Booking <span id="slot"></span></strong></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="col-form-label">timeslot:</label>
                            <input type="text" class="form-control bg-light" name="timeslot" id="timeslot" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">heading:</label>
                            <input type="text" class="form-control" name="heading" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">name:</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">email:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="submit">submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"><strong class="text-danger">Booking <span id="slot2"></span></strong></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <div class="mb-3">
                            <label class="col-form-label">timeslot:</label>
                            <input type="text" class="form-control bg-light" name="timeslot" id="timeslot2" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">heading:</label>
                            <input type="text" class="form-control bg-light" id="heading2" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">name:</label>
                            <input type="text" class="form-control bg-light" id="name2" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">email:</label>
                            <input type="email" class="form-control bg-light" id="email2" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(".book").click(function() {
            var timeslot = $(this).attr("data-timeslot");
            var date = $(this).attr("data-date");
            $("#slot").html(date);
            $("#timeslot").val(timeslot);
        })

        $(".booked").click(function() {
            var timeslot = $(this).attr("data-timeslot");
            var date = $(this).attr("data-date");
            var heading = $(this).attr("data-heading");
            var name = $(this).attr("data-name");
            var email = $(this).attr("data-email");
            $("#slot2").html(date);
            $("#timeslot2").val(timeslot);
            $("#heading2").val(heading);
            $("#name2").val(name);
            $("#email2").val(email);
        })
    </script>
</body>

</html>