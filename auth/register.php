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
        $user_type = $_POST['user_type'];

        $sql = "SELECT 1 FROM users WHERE username = :username";
        $args = [
            ':username' => $username
        ];
        $statement = $db->executePreparedQuery($sql, $args);



        if ($statement->rowCount() <= 0) {

            try {

                $sql = "INSERT INTO users(username, password, display_name, mobile_number, type) VALUES(:username, :password, :display_name, :mobile_number, :type)";
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $args = [
                    ':username' => $username,
                    ':password' => $hashed_password,
                    ':display_name' => $display_name,
                    ':mobile_number' => $mobile_number,
                    ':type' => $user_type,
                ];

                $statement = $db->executePreparedQuery($sql, $args);

                $lastInsertId = $db->getConn()->lastInsertId();
                $user = [
                        'id' => $lastInsertId,
                        'username' => $username,
                        'display_name' => $display_name,
                        'mobile_number' => $mobile_number,
                        'type' => $user_type
                ];
                $_SESSION['user'] = $user;
                header('Location: ../dashboard/index.php');

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
        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio1" value="BUYER">
                <label class="form-check-label" for="inlineRadio1">BUYER</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="user_type" id="inlineRadio2" value="SELLER">
                <label class="form-check-label" for="inlineRadio2">SELLER</label>
            </div>
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