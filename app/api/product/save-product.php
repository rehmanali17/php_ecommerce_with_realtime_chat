<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    try {

        $temp_file = $_FILES['file']['tmp_name'];

        $destination = '../../products/images/' . time() . '_' . $_FILES['file']['name'];

        if(move_uploaded_file($temp_file, $destination)){

            $productData = json_decode($_POST['productData'], true);
            $conn = $db->getConn();
            $query = $conn->prepare("INSERT INTO products (name, description, price, quantity, file_path, created_by) VALUES (?, ?, ?, ?, ?, ?)");
            $query->execute(
                array(
                    htmlentities($productData['name']),
                    htmlentities($productData['description']),
                    htmlentities($productData['price']),
                    htmlentities($productData['quantity']),
                    htmlentities(substr($destination, 3)),
                    htmlentities($_SESSION['user']['id'])
                )
            );

            $_SESSION['alert-message'] = 'Product Added successfully';
            $_SESSION['alert-type'] = 'alert-success';

        }else{
            $error = error_get_last();
            $_SESSION['alert-message'] = $error;
            $_SESSION['alert-type'] = 'alert-danger';
        }
    }catch (Exception $exception){
        $_SESSION['alert-message'] = $exception->getMessage();
        $_SESSION['alert-type'] = 'alert-danger';
    }
} else {
    $_SESSION['alert-message'] = 'Unknown error occurred while saving the file';
    $_SESSION['alert-type'] = 'alert-danger';
}



