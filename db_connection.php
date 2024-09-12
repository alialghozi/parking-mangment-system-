<?php
// Database connection parameters
$servername = "localhost";  // Use "localhost" if the database is on the same server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "parking_management"; // Replace with your actual database name

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection - if it fails, show an error message
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
