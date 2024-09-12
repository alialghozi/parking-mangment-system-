<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for updating a parking slot
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $slot_id = $_POST['slot_id'];
    $slot_number = $_POST['slot_number'];
    $is_available = $_POST['is_available'];

    // Update the parking slot in the database
    $query = "UPDATE parking_slot SET slot_number = ?, is_available = ? WHERE slot_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $slot_number, $is_available, $slot_id);

    if ($stmt->execute()) {
        echo "Parking slot '$slot_number' updated successfully.";
    } else {
        echo "Error: Could not update parking slot.";
    }

    $stmt->close();
}

$conn->close();
?>
