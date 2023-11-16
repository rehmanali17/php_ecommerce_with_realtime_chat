<?php
    require 'helpers/logged-in.php';
    require 'database/dbconfig.php';
    $db = new DatabaseConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login</title>
    <link href="assets/css/bootstrap-5.3.0.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 50%;
        }
    </style>
</head>
<body>

<div class="container login-container">
    <h2 class="text-center mb-4">User Login</h2>
    <?php
    if (isset($_POST['login-btn'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT id,username,password,display_name,mobile_number FROM users WHERE username = ?";
        $args = array($username);
        $statement = $db->executePreparedQuery($sql, $args);

        if ($statement->rowCount() > 0) {
            // Fetch the result
            $row = $statement->fetch(PDO::FETCH_ASSOC);

            $stored_hashed_password = $row['password'];

            if (password_verify($password, $stored_hashed_password)) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user'] = $row;
                echo '<script>window.location.href = "dashboard/index.php";</script>';
                exit;
            } else {
                echo "
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            Incorrect password entered for user: $username
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                      ";
            }
        } else {
            echo "
                        <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                            There is no user in the database with username: $username
                            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                        </div>
                  ";
        }
    }
    ?>
    <form method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" name="login-btn" class="btn btn-primary btn-block">Login</button>
    </form>
    <div class="mt-3">
        <a href="auth/register.php">Don't have an account? Register</a>
    </div>
</div>

<script src="assets/js/bootstrap-5.3.0.min.js"></script>
</body>
</html>
