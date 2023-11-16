<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json');
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

try{
    $request_data = file_get_contents('php://input');

    $data = json_decode($request_data, true);
    $productId = $data['id'];

    $sql = "DELETE FROM products where id = ?";
    $queryArgs = array($productId);
    $statement = $db->executePreparedQuery($sql, $queryArgs);

    $_SESSION['alert-message'] = 'Product deleted successfully';
    $_SESSION['alert-type'] = 'alert-success';

} catch (Exception $e){
    $error = error_get_last();
    $_SESSION['alert-message'] = $error;
    $_SESSION['alert-type'] = 'alert-danger';
}
