<?php
session_start();
header('Content-Type: application/json');
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

try{
    $request_data = file_get_contents('php://input');

    $data = json_decode($request_data, true);
    $id = $data['id'];

    $sql = "SELECT * FROM products WHERE id = :id";
    $queryArgs = [
        ':id' => $id
    ];
    $statement = $db->executePreparedQuery($sql, $queryArgs);
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    echo json_encode($row);
} catch (Exception $e){
    $error = error_get_last();
    var_dump($error);
}
