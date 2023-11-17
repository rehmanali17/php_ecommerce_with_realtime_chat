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
    $id = $data['id'];
    $conn = $db->getConn();
    $query = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $query->execute(
        array(
            htmlentities($id)
        )
    );
    $result = $query;
    $row = $result->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
} catch (Exception $e){
    $error = error_get_last();
    var_dump($error);
}
