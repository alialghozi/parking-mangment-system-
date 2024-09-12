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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = intval($_POST['vehicle_id']);

    // Check if the vehicle_id exists in the vehicle table
    $check_vehicle_query = "SELECT vehicle_id, entry_time, exit_time FROM vehicle WHERE vehicle_id = ?";
    $stmt = $conn->prepare($check_vehicle_query);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicle_data = $result->fetch_assoc();
        
        // Check if the vehicle has already exited (exit_time is not NULL)
        if ($vehicle_data['exit_time']) {
            echo json_encode(['error' => "Vehicle ID $vehicle_id has already exited. No further payment required."]);
        } else {
            // Calculate the parking cost based on the entry_time and current time
            $entry_time = strtotime($vehicle_data['entry_time']);
            $current_time = time();
            $hours_parked = ceil(($current_time - $entry_time) / 3600); // Calculate total hours parked
            
            $cost_per_hour = 5; // RM 5 per hour
            $total_cost = $hours_parked * $cost_per_hour;

            // Ensure total cost is non-negative
            if ($total_cost <= 0) {
                $total_cost = 0;
            }

            // Send response with total cost and hours parked
            echo json_encode([
                'success' => true,
                'total_cost' => $total_cost,
                'hours_parked' => $hours_parked
            ]);
        }
    } else {
        // If vehicle_id does not exist
        echo json_encode(['error' => "Vehicle ID $vehicle_id does not exist in the system."]);
    }

    $stmt->close();
}

$conn->close();
?>
