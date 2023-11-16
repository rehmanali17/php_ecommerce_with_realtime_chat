<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

$chatData = json_decode($_POST['chatData'], true);
$outgoing_id = $_SESSION['user']['id'];
$sql = "INSERT INTO messages (incoming_id, outgoing_id, message) VALUES (?, ?, ?)";
$queryArgs = array($chatData['incoming_id'], $outgoing_id, $chatData['message']);
$statement = $db->executePreparedQuery($sql, $queryArgs);
