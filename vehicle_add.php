<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve inputs
    $license_plate = $_POST['license_plate'];
    $vehicle_type = $_POST['vehicle_type'];
    $slot_id = intval($_POST['slot_id']); // Slot ID, make sure it's an integer

    // Ensure Slot ID, License Plate, and Vehicle Type are provided
    if (!empty($license_plate) && !empty($vehicle_type) && $slot_id > 0) {
        
        // Check if the Slot ID exists and is available
        $check_slot_query = "SELECT is_available FROM parking_slot WHERE slot_id = ?";
        $stmt = $conn->prepare($check_slot_query);
        $stmt->bind_param("i", $slot_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $slot = $result->fetch_assoc();

            if ($slot['is_available']) {
                // Proceed to add vehicle entry
                $insert_vehicle_query = "INSERT INTO vehicle (license_plate, vehicle_type, slot_id) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insert_vehicle_query);
                $stmt->bind_param("ssi", $license_plate, $vehicle_type, $slot_id);

                if ($stmt->execute()) {
                    // Update the slot availability to false (not available)
                    $update_slot_query = "UPDATE parking_slot SET is_available = 0 WHERE slot_id = ?";
                    $stmt = $conn->prepare($update_slot_query);
                    $stmt->bind_param("i", $slot_id);
                    $stmt->execute();

                    echo "Vehicle with license plate '$license_plate' has been logged into Slot ID $slot_id.";
                } else {
                    echo "Error logging vehicle: " . $stmt->error;
                }
            } else {
                echo "Error: Slot ID $slot_id is already occupied.";
            }
        } else {
            echo "Error: Slot ID $slot_id does not exist.";
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request method. Please submit the form.";
}

$conn->close();
?>
