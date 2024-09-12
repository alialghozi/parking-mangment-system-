<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding a parking area
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $area_name = $_POST['area_name'];
    $total_slots = intval($_POST['total_slots']); // Get the number of slots from the form and ensure it's an integer

    // Validate inputs
    if (empty($area_name) || $total_slots <= 0) {
        echo "Invalid area name or total slots.";
        exit;
    }

    // Insert the new parking area into the parking_area table
    $query = "INSERT INTO parking_area (area_name, total_slots) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $area_name, $total_slots);

    if ($stmt->execute()) {
        // Get the last inserted area_id (for linking the slots to the area)
        $area_id = $conn->insert_id;

        // Automatically insert the number of parking slots for this area
        for ($i = 1; $i <= $total_slots; $i++) {
            $slot_number = "Slot " . $i;
            $is_available = 1; // Default to available

            // Insert each slot into the parking_slot table, linked by area_id
            $slot_query = "INSERT INTO parking_slot (slot_number, is_available, area_id) VALUES (?, ?, ?)";
            $slot_stmt = $conn->prepare($slot_query);
            $slot_stmt->bind_param("sii", $slot_number, $is_available, $area_id);

            if (!$slot_stmt->execute()) {
                echo "Error: Could not add parking slot '$slot_number'.";
            }
        }

        // Success message after adding the area and slots
        echo "Parking area '$area_name' with $total_slots slots added successfully.";
    } else {
        echo "Error: Could not add parking area.";
    }

    $stmt->close();
}

$conn->close();
?>
