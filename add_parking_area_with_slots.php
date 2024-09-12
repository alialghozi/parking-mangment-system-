<?php
include('test_db.php');  // Include your database connection file

// Function to sanitize inputs
function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form input
    $areaName = sanitize($_POST['area_name']);
    $totalSlots = intval(sanitize($_POST['total_slots']));  // Ensure the total slots is an integer

    // Check for valid input
    if (empty($areaName) || $totalSlots <= 0) {
        echo json_encode(['error' => "Invalid area name or total slots."]);
        exit();
    }

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert the parking area
        $sql = "INSERT INTO parking_area (area_name, total_slots) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$areaName, $totalSlots]);

        // Get the last inserted area_id
        $areaId = $pdo->lastInsertId();

        // Insert the corresponding parking slots
        for ($i = 1; $i <= $totalSlots; $i++) {
            $slotNumber = sprintf('%03d', $i);  // Format slot number (e.g., 001, 002)
            $sql = "INSERT INTO parking_slot (slot_number, is_available, area_id) VALUES (?, 1, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$slotNumber, $areaId]);
        }

        // Commit transaction
        $pdo->commit();
        echo json_encode(['success' => "Parking area '{$areaName}' with {$totalSlots} slots added successfully."]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['error' => "Failed to add parking area and slots: " . $e->getMessage()]);
    }
}
?>
