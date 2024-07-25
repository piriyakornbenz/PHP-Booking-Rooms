<?php

    session_start();
    require('./config.php');

    if (isset($_SESSION['login'])) {
        $employee_id = $_SESSION['login'];
    }else {
        header('location: login.php');
        exit();
    }

    if (isset($_POST['add'])) {
        $room_name = $_POST['room_name'];
        $room_description = $_POST['room_description'];

        $stmt = $conn->prepare('INSERT INTO rooms (room_name, room_description) VALUES (:room_name, :room_description)');
        $stmt->bindParam(':room_name', $room_name);
        $stmt->bindParam(':room_description', $room_description);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Add employee successful.";
            header('location: rooms.php');
            exit();
        }else {
            $_SESSION['error'] = "Add employee failed.";
        }
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
                                <span class="fs-5">Booking Room Web</span>
                            </a>
                            <hr class="text-white">
                        </div>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="dashboard.php" class="nav-link text-white">
                                    <i class="fa-solid fa-users me-2"></i>
                                    <span><small>Employee</small></span>
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="rooms.php" class="nav-link text-white active">
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
                        <li class="breadcrumb-item"><a href="dashboard.php">Employee</a></li>
                        <li class="breadcrumb-item active">Add Employee</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h2>Add Employee</h2>
                    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="container shadow rounded">
                    <form action="" method="post">
                        <div class="container">
                            <div class="row">
                                <?php if(isset($_SESSION['success'])) { ?>
                                    <div class="alert alert-success mt-2">
                                        <?php 
                                            echo $_SESSION['success'];
                                            unset($_SESSION['success']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if(isset($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger mt-2">
                                        <?php 
                                            echo $_SESSION['error'];
                                            unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <div class="col-md-6 mt-4">
                                    <div class="mb-4">
                                        <label>room name</label>
                                        <input type="text" name="room_name" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <div class="mb-4">
                                        <label>room description</label>
                                        <input type="text" name="room_description" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="text-end mb-4">
                                    <a href="rooms.php" class="btn btn-secondary me-2">Back</a>
                                    <button type="submit" class="btn btn-primary" name="add">Add</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </main>
        </div>
    </div>



</body>

</html>