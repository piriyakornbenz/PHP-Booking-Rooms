<?php

    session_start();
    require('./config.php');

    if (isset($_SESSION['admin_login'])) {
        $employee_id = $_SESSION['admin_login'];
    }else {
        header('location: login.php');
        exit();
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
                                <a href="employee.php" class="nav-link text-white active">
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
                        <li class="breadcrumb-item active">Employee</li>
                    </ol>
                </nav>
                <div class="pt-2 pb-2 mb-2 border-bottom">
                    <h2 class="text-center">Employee</h2>
                    <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="container shadow rounded">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4>Table Employee</h4>
                                <a href="add_employee.php" class="btn btn-primary my-4"><i class="fa-solid fa-plus"></i> Add Employee</a>
                            </div>

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

                            </div>
                            <div class="container">
                                <table class="table table-striped table-bordered table-hover shadow-sm">
                                    <thead>
                                    <tr class="text-center">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Booking</th>
                                        <th scope="col">Edit</th>
                                        <th scope="col">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $count = 1;
                                        $stmt = $conn->prepare("SELECT * FROM employee");
                                        $stmt->execute();
                                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach($result as $row) {
                                    ?>
                                    <tr class="text-center">
                                        <td class="align-middle"><?= $count++ ?></td>
                                        <td class="align-middle"><?= $row['name'] ?></td>
                                        <td class="align-middle"><?= $row['email'] ?></td>
                                        <td class="align-middle"><?= $row['role'] ?></td>
                                        <td class="align-middle"><a href="view_employee.php?user_id=<?= $row['id'] ?>" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> view</a></td>
                                        <td class="align-middle"><a href="update_employee.php?update_id=<?= $row['id'] ?>" class="btn btn-warning"><i class="fa-regular fa-pen-to-square"></i> edit</a></td>
                                        <td class="align-middle"><a href="delete_employee.php?delete_id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete?')"><i class="fa-solid fa-trash"></i> delete</a></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>

