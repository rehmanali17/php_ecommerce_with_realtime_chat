<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

try {

    $sql = "UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?";
    $productData = json_decode($_POST['productData'], true);
    $conn = $db->getConn();
    $query = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, quantity = ? WHERE id = ?");
    $query->execute(
        array(
            htmlentities($productData['name']),
            htmlentities($productData['description']),
            htmlentities($productData['price']),
            htmlentities($productData['quantity']),
            htmlentities($productData['id'])
        )
    );
    $_SESSION['alert-message'] = 'Product updated successfully';
    $_SESSION['alert-type'] = 'alert-success';
} catch (Exception $exception) {
    $_SESSION['alert-message'] = $exception->getMessage();
    $_SESSION['alert-type'] = 'alert-danger';
}



