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

// Check which report to generate
if (isset($_GET['report'])) {
    $reportType = $_GET['report'];

    switch ($reportType) {
        case 'slot_occupancy':
            generateSlotOccupancyReport($conn);
            break;
        case 'revenue':
            generateRevenueReport($conn);
            break;
        case 'vehicle_log':
            generateVehicleLogReport($conn);
            break;
        default:
            echo "Invalid report type.";
    }
}

// Function to generate the slot occupancy report with area names and slot ID
function generateSlotOccupancyReport($conn) {
    // Query to fetch slot id, slot number, availability, and area name
    $sql = "SELECT ps.slot_id, ps.slot_number, ps.is_available, pa.area_name 
            FROM parking_slot ps 
            JOIN parking_area pa ON ps.area_id = pa.area_id";  // Adjust the area_id as per your schema
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3 style='color:white;'>Slot Occupancy Report</h3>";
        echo "<table style='border-collapse: collapse; width: 100%;'>
                <tr style='background-color: #2980b9; color: #ecf0f1;'>
                    <th style='padding: 10px;'>Slot ID</th>
                    <th style='padding: 10px;'>Area Name</th>
                    <th style='padding: 10px;'>Slot Number</th>
                    <th style='padding: 10px;'>Status</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            $availability = $row['is_available'] ? 'Available' : 'Occupied';
            echo "<tr style='border-bottom: 1px solid #bdc3c7;'>
                    <td style='padding: 10px; text-align: center;'>{$row['slot_id']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['area_name']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['slot_number']}</td>
                    <td style='padding: 10px; text-align: center; color:" . ($row['is_available'] ? '#27ae60' : '#c0392b') . ";'>{$availability}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: #c0392b;'>No slots found.</p>";
    }
}

// Function to generate the revenue report
function generateRevenueReport($conn) {
    // Query to fetch payment data
    $sql = "SELECT vehicle_id, amount_paid, payment_method, exit_time FROM payment";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3 style='color:white;'>Revenue Report</h3>";
        echo "<table style='border-collapse: collapse; width: 100%;'>
                <tr style='background-color: #2980b9; color: #ecf0f1;'>
                    <th style='padding: 10px;'>Vehicle ID</th>
                    <th style='padding: 10px;'>Amount Paid (RM)</th>
                    <th style='padding: 10px;'>Payment Method</th>
                    <th style='padding: 10px;'>Exit Time</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border-bottom: 1px solid #bdc3c7;'>
                    <td style='padding: 10px; text-align: center;'>{$row['vehicle_id']}</td>
                    <td style='padding: 10px; text-align: center;'>RM {$row['amount_paid']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['payment_method']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['exit_time']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: #c0392b;'>No payments found.</p>";
    }
}

// Function to generate the vehicle log report
function generateVehicleLogReport($conn) {
    // Query to fetch vehicle data
    $sql = "SELECT vehicle_id, license_plate, vehicle_type, entry_time, exit_time FROM vehicle";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h3 style='color:white;'>Vehicle Log Report</h3>";
        echo "<table style='border-collapse: collapse; width: 100%;'>
                <tr style='background-color: #2980b9; color: #ecf0f1;'>
                    <th style='padding: 10px;'>Vehicle ID</th>
                    <th style='padding: 10px;'>License Plate</th>
                    <th style='padding: 10px;'>Vehicle Type</th>
                    <th style='padding: 10px;'>Entry Time</th>
                    <th style='padding: 10px;'>Exit Time</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr style='border-bottom: 1px solid #bdc3c7;'>
                    <td style='padding: 10px; text-align: center;'>{$row['vehicle_id']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['license_plate']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['vehicle_type']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['entry_time']}</td>
                    <td style='padding: 10px; text-align: center;'>{$row['exit_time']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: #c0392b;'>No vehicles found.</p>";
    }
}

$conn->close();
?>
