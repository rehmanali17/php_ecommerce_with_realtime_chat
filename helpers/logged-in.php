<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard/index.php');
    exit;
}