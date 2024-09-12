<?php
session_start(); // Start session to store messages

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
    $amount_paid = floatval($_POST['amount_paid']);
    $payment_method = $_POST['payment_method'];

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
            $_SESSION['message'] = "Error: Vehicle ID $vehicle_id has already exited. No further payment required.";
        } else {
            // Calculate the parking cost based on the entry_time and current time
            $entry_time = strtotime($vehicle_data['entry_time']);
            $current_time = time(); // Current time
            $hours_parked = ceil(($current_time - $entry_time) / 3600); // Calculate total hours parked, round up using ceil()

            if ($hours_parked <= 0) {
                $hours_parked = 1; // Ensure at least 1 hour is counted even if it's less than 1 hour
            }

            $cost_per_hour = 5; // RM 5 per hour
            $total_cost = $hours_parked * $cost_per_hour;

            // Check if the amount paid is sufficient
            if ($amount_paid >= $total_cost) {
                // If the payment is successful, update the payment table and mark vehicle as exited
                $exit_time = date('Y-m-d H:i:s'); // Current time as exit time
                $payment_query = "INSERT INTO payment (vehicle_id, amount_paid, payment_method, exit_time) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($payment_query);
                $stmt->bind_param("idss", $vehicle_id, $amount_paid, $payment_method, $exit_time);

                if ($stmt->execute()) {
                    // Update exit time in the vehicle table
                    $update_exit_time_query = "UPDATE vehicle SET exit_time = ? WHERE vehicle_id = ?";
                    $stmt = $conn->prepare($update_exit_time_query);
                    $stmt->bind_param("si", $exit_time, $vehicle_id);
                    $stmt->execute();

                    // Mark the slot as available again
                    $get_slot_query = "SELECT slot_id FROM vehicle WHERE vehicle_id = ?";
                    $stmt = $conn->prepare($get_slot_query);
                    $stmt->bind_param("i", $vehicle_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $vehicle_data = $result->fetch_assoc();
                        $slot_id = $vehicle_data['slot_id'];

                        // Update slot availability to 'available'
                        $update_slot_query = "UPDATE parking_slot SET is_available = 1 WHERE slot_id = ?";
                        $stmt->bind_param("i", $slot_id);
                        $stmt->execute();
                    }

                    $_SESSION['message'] = "Payment of RM $amount_paid for vehicle ID $vehicle_id has been processed successfully. Vehicle has exited at $exit_time.";
                } else {
                    $_SESSION['message'] = "Error processing payment: " . $stmt->error;
                }
            } else {
                // If the amount paid is insufficient
                $_SESSION['message'] = "Error: Insufficient payment. Total cost is RM $total_cost, but you only paid RM $amount_paid. Please pay the remaining amount.";
            }
        }
    } else {
        // If vehicle_id does not exist
        $_SESSION['message'] = "Error: Vehicle ID $vehicle_id does not exist in the system. Please add the vehicle first.";
    }

    $stmt->close();

    // Redirect to index.php to show the message
    header("Location: index.php");
    exit();
}

$conn->close();
?>
