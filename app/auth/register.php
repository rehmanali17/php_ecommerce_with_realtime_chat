<?php
    require '../helpers/logged-in.php';
    require '../database/dbconfig.php';
    $db = new DatabaseConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <link href="../assets/css/bootstrap-5.3.0.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .registration-container {
            max-width: 50%;
        }
    </style>
</head>
<body>

<div class="container registration-container">
    <h2 class="text-center mb-4">User Registration</h2>
    <?php
    if (isset($_POST['register-btn'])) {

        $display_name = $_POST['display_name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $mobile_number = $_POST['mobile_number'];

        $conn = $db->getConn();
        $query = $conn->prepare("SELECT 1 FROM users WHERE username = ?");
        $query->execute(
            array(
                htmlentities($username)
            )
        );
        $result = $query;


        if ($result->rowCount() <= 0) {

            try {

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $query = $conn->prepare("INSERT INTO users(username, password, display_name, mobile_number) VALUES(?, ?, ?, ?)");
                $query->execute(
                    array(
                        htmlentities($username),
                        htmlentities($hashed_password),
                        htmlentities($display_name),
                        htmlentities($mobile_number)
                    )
                );

                $lastInsertId = $conn->lastInsertId();
                $user = [
                     'id' => $lastInsertId,
                     'username' => $username,
                     'display_name' => $display_name,
                     'mobile_number' => $mobile_number
                ];
                $_SESSION['user'] = $user;
                echo '<script>window.location.href = "../dashboard/index.php";</script>';
                exit;

            } catch (Exception $e) {
                echo "
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Registeration failed with username: $username. Please try again.
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                      ";
            }
        } else {
            echo "
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Username: $username already taken
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                  ";
        }
    }
    ?>
    <form method="post">
        <div class="mb-3">
            <label for="display_name" class="form-label">Display Name</label>
            <input type="text" class="form-control" id="display_name" name="display_name" required>
        </div>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="mobile_number" class="form-label">Mobile Number</label>
            <input type="text" class="form-control" id="mobile_number" name="mobile_number" required>
        </div>
        <button type="submit" name="register-btn" class="btn btn-primary btn-block">Register</button>
    </form>
    <div class="mt-3">
        <a href="../index.php">Already have an account? Login</a>
    </div>
</div>

<script src="../assets/js/bootstrap-5.3.0.min.js"></script>
</body>
</html>
