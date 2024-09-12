<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'parking_management');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form data based on action
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // Handle deleting an existing vehicle entry
    if ($action == 'delete') {
        $vehicle_id = $_POST['vehicle_id'];

        if (!empty($vehicle_id)) {
            // First, check if the vehicle exists in the database
            $checkVehicle = $conn->prepare("SELECT * FROM vehicle WHERE vehicle_id = ?");
            $checkVehicle->bind_param("i", $vehicle_id);
            $checkVehicle->execute();
            $vehicleResult = $checkVehicle->get_result();

            if ($vehicleResult->num_rows === 0) {
                // Vehicle ID does not exist
                echo "Error: Vehicle ID {$vehicle_id} does not exist in the system.";
            } else {
                // Check if the vehicle is tied to any other data (like foreign keys)
                $checkPayment = $conn->prepare("SELECT * FROM payment WHERE vehicle_id = ?");
                $checkPayment->bind_param("i", $vehicle_id);
                $checkPayment->execute();
                $result = $checkPayment->get_result();

                // If the vehicle is tied to payments or other data, prevent deletion
                if ($result->num_rows > 0) {
                    echo "Error: This vehicle cannot be deleted because it is associated with payments.";
                } else {
                    // Delete the vehicle record from the database
                    $stmt = $conn->prepare("DELETE FROM vehicle WHERE vehicle_id = ?");
                    $stmt->bind_param("i", $vehicle_id);

                    if ($stmt->execute()) {
                        echo "Vehicle entry deleted successfully.";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                }
            }
        } else {
            echo "Error: Vehicle ID is required.";
        }
    } else {
        echo "Error: Invalid action.";
    }
} else {
    echo "Error: Invalid request method.";
}

$conn->close();
?>
