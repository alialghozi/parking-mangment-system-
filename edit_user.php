<?php
session_start();
include 'db_connection.php'; 

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Fetch user details if 'id' is set in the query string
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prepare the query to fetch user data without password (since it's hashed)
    $query = "SELECT first_name, last_name, email, role FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    // Update user details if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST['fName'];
        $last_name = $_POST['lName'];
        $email = $_POST['email'];
        $role = $_POST['role'];
        $password = $_POST['password'];

        // If a new password is provided, hash and update it
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("sssssi", $first_name, $last_name, $email, $role, $hashed_password, $user_id);
        } else {
            // Update without password change
            $update_query = "UPDATE users SET first_name = ?, last_name = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssssi", $first_name, $last_name, $email, $role, $user_id);
        }
        
        // Execute update and redirect to dashboard
        $stmt->execute();
        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="edit-style.css"> <!-- Link to external CSS file -->
    <style>
        .inputbox {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .inputbox input,
        .inputbox select {
            width: 80%;
            padding: 10px;
            margin-right: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color:white;
            background: transparent;

        }

        .btn-edit-inline {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            
        }

        .btn-edit-inline:hover {
            background-color: #2980b9;
        }

        .password-note {
            font-size: 12px;
            color: white;
            margin-top: -10px;
            margin-bottom: 10px;
            font-size: 14px ;
            font-size: bold;

        }
    </style>
</head>
<body>
<div class="form-container">
    <div class="form-box">
        <h1 class="form-title">Edit User</h1>
        <form method="post">
            <div class="inputbox">
                <input type="text" name="fName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                <label for="fName">First Name</label>
            </div>
            <div class="inputbox">
                <input type="text" name="lName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                <label for="lName">Last Name</label>
            </div>
            <div class="inputbox">
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                <label for="email">Email</label>
            </div>
            <div class="inputbox">
                <select name="role">
                    <option value="employee" <?php echo $user['role'] === 'employee' ? 'selected' : ''; ?>>Employee</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
                <label for="role">Role</label>
            </div>
            <div class="inputbox">
                <input type="password" name="password" placeholder="Enter new password (leave blank to keep current)">
                <label for="password">Password</label>
                <div class="password-note">Leave blank if you don't want to change the password.</div>
                <button type="submit" class="btn-edit-inline">Update</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
