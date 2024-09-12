<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'parking_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Check if the 'action' key is set in the POST request
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Handle adding a new vehicle
        if ($action == 'add') {
            $license_plate = $_POST['license_plate'];
            $vehicle_type = $_POST['vehicle_type'];
            $slot_id = intval($_POST['slot_id']); // Make sure slot_id is an integer

            if (!empty($license_plate) && !empty($vehicle_type) && !empty($slot_id)) {
                // Check if the vehicle is already parked (license_plate uniqueness)
                $check_vehicle_query = "SELECT vehicle_id FROM vehicle WHERE license_plate = ? AND exit_time IS NULL";
                $stmt = $conn->prepare($check_vehicle_query);
                $stmt->bind_param("s", $license_plate);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    // The vehicle is already parked and has not exited yet
                    echo "Error: Vehicle with license plate '$license_plate' is already parked.";
                } else {
                    // Check if the slot is available
                    $check_slot_query = "SELECT is_available FROM parking_slot WHERE slot_id = ?";
                    $stmt = $conn->prepare($check_slot_query);
                    $stmt->bind_param("i", $slot_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $slot_data = $result->fetch_assoc();
                        if ($slot_data['is_available'] == 1) {
                            // Insert the vehicle entry into the database
                            $stmt = $conn->prepare("INSERT INTO vehicle (license_plate, vehicle_type, slot_id, entry_time) VALUES (?, ?, ?, NOW())");
                            $stmt->bind_param("ssi", $license_plate, $vehicle_type, $slot_id);

                            if ($stmt->execute()) {
                                // Mark the slot as unavailable
                                $update_slot_query = "UPDATE parking_slot SET is_available = 0 WHERE slot_id = ?";
                                $stmt = $conn->prepare($update_slot_query);
                                $stmt->bind_param("i", $slot_id);
                                $stmt->execute();

                                echo "Vehicle entry added successfully. Slot $slot_id is now unavailable.";
                            } else {
                                echo "Error adding vehicle: " . $stmt->error;
                            }
                        } else {
                            echo "Error: Slot $slot_id is already occupied.";
                        }
                    } else {
                        echo "Error: Slot $slot_id does not exist.";
                    }
                }

                $stmt->close();
            } else {
                echo "Error: Missing form data.";
            }
        }

        // Handle updating an existing vehicle entry
        elseif ($action == 'update') {
            $vehicle_id = $_POST['vehicle_id'];
            $license_plate = $_POST['license_plate'];
            $vehicle_type = $_POST['vehicle_type'];

            if (!empty($vehicle_id) && !empty($license_plate) && !empty($vehicle_type)) {
                // Update vehicle entry in the database
                $stmt = $conn->prepare("UPDATE vehicle SET license_plate = ?, vehicle_type = ? WHERE vehicle_id = ?");
                $stmt->bind_param("ssi", $license_plate, $vehicle_type, $vehicle_id);

                if ($stmt->execute()) {
                    echo "Vehicle entry updated successfully.";
                } else {
                    echo "Error updating vehicle: " . $stmt->error;
                }

                $stmt->close();
            } else {
                echo "Error: Missing form data.";
            }
        }

        // Handle deleting an existing vehicle entry
        elseif ($action == 'delete') {
            $vehicle_id = $_POST['vehicle_id'];

            if (!empty($vehicle_id)) {
                // Get the slot_id associated with this vehicle before deleting
                $get_slot_query = "SELECT slot_id FROM vehicle WHERE vehicle_id = ?";
                $stmt = $conn->prepare($get_slot_query);
                $stmt->bind_param("i", $vehicle_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $vehicle_data = $result->fetch_assoc();
                    $slot_id = $vehicle_data['slot_id'];

                    // Delete the vehicle from the database
                    $stmt = $conn->prepare("DELETE FROM vehicle WHERE vehicle_id = ?");
                    $stmt->bind_param("i", $vehicle_id);

                    if ($stmt->execute()) {
                        // Mark the slot as available again
                        $update_slot_query = "UPDATE parking_slot SET is_available = 1 WHERE slot_id = ?";
                        $stmt = $conn->prepare($update_slot_query);
                        $stmt->bind_param("i", $slot_id);
                        $stmt->execute();

                        echo "Vehicle entry deleted successfully. Slot $slot_id is now available.";
                    } else {
                        echo "Error deleting vehicle: " . $stmt->error;
                    }
                } else {
                    echo "Error: Vehicle ID $vehicle_id does not exist.";
                }

                $stmt->close();
            } else {
                echo "Error: Missing vehicle ID.";
            }
        } else {
            echo "Error: Invalid action.";
        }
    } else {
        echo "Error: 'action' is not set.";
    }
} else {
    echo "Error: Invalid request method.";
}

$conn->close();
?>
