<?php

session_start();
require('./config.php');

if (isset($_SESSION['login'])) {
    $user_id = $_SESSION['login'];
} else {
    header('location: login.php');
    exit();
}

if (isset($_GET['update_id'])) {
    $id = $_GET['update_id'];

    $stmt = $conn->prepare("SELECT * FROM slottime WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $row = $stmt->fetch();
}

if (isset($_POST['update'])) {
    $start = $_POST['start'];
    $end = $_POST['end'];
    $duration = $_POST['duration'];

    $stmt = $conn->prepare("UPDATE slottime SET start=:start, end=:end, duration=:duration WHERE id=:id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':end', $end);
    $stmt->bindParam(':duration', $duration);

    if ($stmt->execute()) {
        $_SESSION['success'] = "update slottime successful.";
        header('location: setting.php');
        exit();
    } else {
        $_SESSION['error'] = "update slottime failed.";
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
                                <span class="fs-5">Dashboard</span>
                            </a>
                            <hr class="text-white">
                        </div>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="dashboard.php" class="nav-link text-white">
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
                                <a href="setting.php" class="nav-link text-white active">
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
                        <li class="breadcrumb-item"><a href="setting.php">Slottime</a></li>
                        <li class="breadcrumb-item active">Update Slottime</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h2>Update Slottime</h2>
                    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="container shadow rounded">
                    <form action="" method="post">
                        <div class="container">
                            <div class="row">
                                <?php if (isset($_SESSION['success'])) { ?>
                                    <div class="alert alert-success mt-2">
                                        <?php
                                        echo $_SESSION['success'];
                                        unset($_SESSION['success']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <?php if (isset($_SESSION['error'])) { ?>
                                    <div class="alert alert-danger mt-2">
                                        <?php
                                        echo $_SESSION['error'];
                                        unset($_SESSION['error']);
                                        ?>
                                    </div>
                                <?php } ?>
                                <div class="col-md-4 mt-4">
                                    <div class="mb-4">
                                        <label>start time</label>
                                        <select class="form-select" name="start">
                                            <option value="06:00" <?= ($row['start'] == "06:00") ? "selected" : '' ?>>06:00</option>
                                            <option value="06:30" <?= ($row['start'] == "06:30") ? "selected" : '' ?>>06:30</option>
                                            <option value="07:00" <?= ($row['start'] == "07:00") ? "selected" : '' ?>>07:00</option>
                                            <option value="07:30" <?= ($row['start'] == "07:30") ? "selected" : '' ?>>07:30</option>
                                            <option value="08:00" <?= ($row['start'] == "08:00") ? "selected" : '' ?>>08:00</option>
                                            <option value="08:30" <?= ($row['start'] == "08:30") ? "selected" : '' ?>>08:30</option>
                                            <option value="09:00" <?= ($row['start'] == "09:00") ? "selected" : '' ?>>09:00</option>
                                            <option value="09:30" <?= ($row['start'] == "09:30") ? "selected" : '' ?>>09:30</option>
                                            <option value="10:00" <?= ($row['start'] == "10:00") ? "selected" : '' ?>>10:00</option>
                                            <option value="10:30" <?= ($row['start'] == "10:30") ? "selected" : '' ?>>10:30</option>
                                            <option value="11:00" <?= ($row['start'] == "11:00") ? "selected" : '' ?>>11:00</option>
                                            <option value="11:30" <?= ($row['start'] == "11:30") ? "selected" : '' ?>>11:30</option>
                                            <option value="12:00" <?= ($row['start'] == "12:00") ? "selected" : '' ?>>12:00</option>
                                            <option value="12:30" <?= ($row['start'] == "12:30") ? "selected" : '' ?>>12:30</option>
                                            <option value="13:00" <?= ($row['start'] == "13:00") ? "selected" : '' ?>>13:00</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <div class="mb-4">
                                        <label>end time</label>
                                        <select class="form-select" name="end">
                                            <option value="12:00" <?= ($row['end'] == "12:00") ? "selected" : '' ?>>12:00</option>
                                            <option value="12:30" <?= ($row['end'] == "12:30") ? "selected" : '' ?>>12:30</option>
                                            <option value="13:00" <?= ($row['end'] == "13:00") ? "selected" : '' ?>>13:00</option>
                                            <option value="13:30" <?= ($row['end'] == "13:30") ? "selected" : '' ?>>13:30</option>
                                            <option value="14:00" <?= ($row['end'] == "14:00") ? "selected" : '' ?>>14:00</option>
                                            <option value="14:30" <?= ($row['end'] == "14:30") ? "selected" : '' ?>>14:30</option>
                                            <option value="15:00" <?= ($row['end'] == "15:00") ? "selected" : '' ?>>15:00</option>
                                            <option value="15:30" <?= ($row['end'] == "15:30") ? "selected" : '' ?>>15:30</option>
                                            <option value="16:00" <?= ($row['end'] == "16:00") ? "selected" : '' ?>>16:00</option>
                                            <option value="16:30" <?= ($row['end'] == "16:30") ? "selected" : '' ?>>16:30</option>
                                            <option value="17:00" <?= ($row['end'] == "17:00") ? "selected" : '' ?>>17:00</option>
                                            <option value="17:30" <?= ($row['end'] == "17:30") ? "selected" : '' ?>>17:30</option>
                                            <option value="18:00" <?= ($row['end'] == "18:00") ? "selected" : '' ?>>18:00</option>
                                            <option value="18:30" <?= ($row['end'] == "18:30") ? "selected" : '' ?>>18:30</option>
                                            <option value="19:00" <?= ($row['end'] == "19:00") ? "selected" : '' ?>>19:00</option>
                                            <option value="19:30" <?= ($row['end'] == "19:30") ? "selected" : '' ?>>19:30</option>
                                            <option value="20:00" <?= ($row['end'] == "20:00") ? "selected" : '' ?>>20:00</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-4">
                                    <div class="mb-4">
                                        <label>duration (minutes.)</label>
                                        <input type="number" name="duration" class="form-control" value="<?= $row['duration'] ?>" required>
                                    </div>
                                </div>

                                <div class="text-end mb-4">
                                    <a href="setting.php" class="btn btn-secondary me-2">Back</a>
                                    <button type="submit" class="btn btn-primary" name="update">update</button>
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