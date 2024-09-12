<?php
include 'db_connection.php'; // Ensure this file includes the correct database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $first_name = $_POST['fName'];
    $last_name = $_POST['lName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Insert data into users table
    $query = "INSERT INTO users (first_name, last_name, email, password, status, role) VALUES (?, ?, ?, ?, 'pending', 'employee')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $password);
    
    if ($stmt->execute()) {
        echo "Registration successful! Awaiting approval.";
        header("Location: login.html"); // Redirect to login page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}
$conn->close();
?>
