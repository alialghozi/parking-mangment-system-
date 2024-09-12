 <?php
session_start();
include 'db_connection.php'; 

// Ensure the user is logged in and has an admin role
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Fetch all users (including those with approved status)
$query_all = "SELECT id, first_name, last_name, email, role FROM users";
$all_users = $conn->query($query_all);

// Check if query execution failed
if (!$all_users) {
    echo "Error fetching users: " . $conn->error;
    exit();
}

// Fetch pending users for approval
$query_pending = "SELECT id, first_name, last_name, email, status FROM users WHERE status = 'pending'";
$pending_users = $conn->query($query_pending);

// Check if query execution failed
if (!$pending_users) {
    echo "Error fetching pending users: " . $conn->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>

        <!-- Section: Pending Approvals -->
        <h2>Pending User Approvals</h2>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $pending_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td class="action-links">
                            <a href="approve_user.php?id=<?php echo urlencode($row['id']); ?>" class="btn-approve">Approve</a>
                            <a href="reject_user.php?id=<?php echo urlencode($row['id']); ?>" class="btn-reject">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Section: Manage All Users -->
        <h2>Manage All Users</h2>
        <table class="dashboard-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $all_users->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['role']); ?></td>
                        <td class="action-links">
                            <a href="edit_user.php?id=<?php echo urlencode($row['id']); ?>" class="btn-edit">Edit</a>
                            <a href="delete_user.php?id=<?php echo urlencode($row['id']); ?>" class="btn-delete" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Button to go to the homepage -->
        <button onclick="location.href='index.php'" class="btn-home">Go to Home Page</button>
    </div>
</body>
</html>
