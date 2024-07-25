<?php

    session_start();
    require('./config.php');

    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM employee WHERE email=:email");
        $stmt->bindParam('email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (password_verify($password, $row['password'])) {
                
                if ($row['role'] == "employee") {
                    $_SESSION['user_login'] = $row['id'];
                    header('location: index.php');
                    exit;
                }else{
                    $_SESSION['admin_login'] = $row['id'];
                    header('location: dashboard.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = "Incorrect email or password";
            }
        } else {
            $_SESSION['error'] = "Email not found in database";
            header('location: login.php');
            exit;
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Site</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container p-4 mt-4">
        <div class="row d-flex justify-content-center shadow rounded">
            <div class="col-md-6 p-0 ">
                <img src="https://plus.unsplash.com/premium_photo-1670315262884-100dcb04980e?q=80&w=1956&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="w-100 h-100 img-fluid object-fit-cover rounded" alt="">
            </div>
            <div class="col-md-6 p-4">
                <form action="" method="post">

                    <div class="container p-4">

                        <h1 class="text-center mb-3">Login</h1>

                        <!-- error message -->
                        <?php if (isset($_SESSION['error'])) { ?>
                            <div class="alert alert-danger">
                                <?php echo $_SESSION['error'];  ?>
                                <?php unset($_SESSION['error']);  ?>
                            </div>
                        <?php } ?>

                        <div class="col-md-12 col-lg-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Please enter your email" required>
                        </div>

                        <div class="col-md-12 col-lg-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control mb-3" id="password" name="password" placeholder="Please enter your password" required>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12">
                            <button class="btn btn-primary w-100 mt-3" type="submit" name="submit">login</button>
                        </div>

                        <div class="col-12 col-md-12 col-lg-12 mt-4">
                            <p class="text-muted small">Website for booking rooms, copyright 2024.</p>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>