<?php
session_start();
include 'db_connection.php';

// Ensure the user is logged in and has an admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete user by ID
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: dashboard.php");
    exit();
}
?>
