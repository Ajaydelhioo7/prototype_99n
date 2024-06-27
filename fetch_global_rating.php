<?php
session_start();
require 'db_connect.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT globalRating FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($globalRating);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    echo number_format($globalRating, 2);
}
?>
