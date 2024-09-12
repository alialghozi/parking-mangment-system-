<?php
// Test database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management'); // Adjust credentials as necessary

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}

// Close the connection
$conn->close();
?>
