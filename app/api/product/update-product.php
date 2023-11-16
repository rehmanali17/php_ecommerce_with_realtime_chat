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
    $queryArgs = array($productData['name'], $productData['description'], $productData['price'], $productData['quantity'], $productData['id']);
    $statement = $db->executePreparedQuery($sql, $queryArgs);
    $_SESSION['alert-message'] = 'Product updated successfully';
    $_SESSION['alert-type'] = 'alert-success';
} catch (Exception $exception) {
    $_SESSION['alert-message'] = $exception->getMessage();
    $_SESSION['alert-type'] = 'alert-danger';
}



