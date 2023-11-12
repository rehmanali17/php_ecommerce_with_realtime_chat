<?php
session_start();
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

$chatData = json_decode($_POST['chatData'], true);
$outgoing_id = $_SESSION['user']['id'];
$sql = "INSERT INTO messages (incoming_id, outgoing_id, message) VALUES (:incoming_id, :outgoing_id, :message)";
$queryArgs = [
    ':incoming_id' => $chatData['incoming_id'],
    ':outgoing_id' => $outgoing_id,
    ':message' => $chatData['message']
];
$statement = $db->executePreparedQuery($sql, $queryArgs);
