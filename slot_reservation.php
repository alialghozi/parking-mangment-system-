<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if it's a GET request to check availability
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['slot_number'])) {
    $slot_number = $_GET['slot_number'];

    // Check if the slot is available
    $query = "SELECT is_available FROM parking_slot WHERE slot_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $slot_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $availability = $row['is_available'] ? true : false;

        // Return JSON response
        echo json_encode(['available' => $availability]);
    } else {
        // If slot doesn't exist, assume it's unavailable
        echo json_encode(['available' => false]);
    }

    $stmt->close();
} 
// Handle POST request for reservation
else if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slot_number']) && isset($_POST['area_name'])) {
    $slot_number = $_POST['slot_number'];
    $area_name = $_POST['area_name'];

    // Reserve the slot (set is_available to false)
    $query = "UPDATE parking_slot SET is_available = 0 WHERE slot_number = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $slot_number);

    if ($stmt->execute()) {
        echo "Slot $slot_number reserved successfully.";
    } else {
        echo "Error: Could not reserve slot.";
    }

    $stmt->close();
}

$conn->close();
?>
