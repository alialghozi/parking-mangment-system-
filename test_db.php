
<?php
// Test database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}

$conn->close();
?>

