<?php

    session_start();
    require('./config.php');

    if (isset($_SESSION['admin_login'])) {
        $employee_id = $_SESSION['admin_login'];
    }else {
        header('location: login.php');
        exit();
    }

    if (isset($_GET['update_id'])) {
        $id = $_GET['update_id'];

        $stmt = $conn->prepare("SELECT * FROM employee WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch();
    }

    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE employee SET name=:name, email=:email, password=:password, role=:role WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "update employee successful.";
            header('location: dashboard.php');
            exit();
        }else {
            $_SESSION['error'] = "update employee failed.";
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
                        <li class="breadcrumb-item"><a href="employee.php">Employee</a></li>
                        <li class="breadcrumb-item active">Update Employee</li>
                    </ol>
                </nav>
                <div class="pt-2 pb-2 mb-2 border-bottom">
                    <h2 class="text-center">Update Employee</h2>
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
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="<?= $row['name'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-4">
                                    <div class="mb-4">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="<?= $row['email'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control"  required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="country">Role</label>
                                        <select id="country" name="role" class="form-select" required>
                                            <option value="">Select a role</option>
                                            <option value="employee" <?php if ($row['role'] == 'employee') echo 'selected'; ?>>Employee</option>
                                            <option value="admin" <?php if ($row['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="text-end mb-4">
                                    <a href="employee.php" class="btn btn-secondary me-2">Back</a>
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