<?php
$conn = new mysqli('localhost', 'root', '', 'parking_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = $_POST['vehicle_id'];
    $license_plate = $_POST['license_plate'];
    $vehicle_type = $_POST['vehicle_type'];

    if (!empty($vehicle_id) && !empty($license_plate) && !empty($vehicle_type)) {
        $stmt = $conn->prepare("UPDATE vehicle SET license_plate = ?, vehicle_type = ? WHERE vehicle_id = ?");
        $stmt->bind_param("ssi", $license_plate, $vehicle_type, $vehicle_id);

        if ($stmt->execute()) {
            echo "Vehicle updated successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
