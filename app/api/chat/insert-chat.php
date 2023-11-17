<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../../database/dbconfig.php';
$db = new DatabaseConnection();

$chatData = json_decode($_POST['chatData'], true);
$outgoing_id = $_SESSION['user']['id'];
$conn = $db->getConn();
$query = $conn->prepare("INSERT INTO messages (incoming_id, outgoing_id, message) VALUES (?, ?, ?)");
$query->execute(
    array(
        htmlentities($chatData['incoming_id']),
        htmlentities($outgoing_id),
        htmlentities($chatData['message'])
    )
);
