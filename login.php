<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL statement to fetch user by email
    $query = "SELECT id, first_name, last_name, email, password, role, status FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If a user is found with that email
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify the password entered matches the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['email'] = $user['email'];

            // Check the user's role and status
            if ($user['role'] === 'admin') {
                // If admin, redirect to dashboard
                header("Location: dashboard.php");
            } elseif ($user['role'] === 'employee' && $user['status'] === 'approved') {
                // If employee and account is approved, redirect to home page
                header("Location: index.php");
            } elseif ($user['role'] === 'employee' && $user['status'] === 'pending') {
                // If employee account is not yet approved
                echo "Your account is pending approval. Please contact the admin.";
                session_destroy();
            } else {
                // If none of the conditions match, redirect back to login
                echo "Invalid role or status.";
                session_destroy();
            }
            exit();
        } else {
            echo "Invalid email or password!";
        }
    } else {
        echo "No user found with that email!";
    }
}
?>
