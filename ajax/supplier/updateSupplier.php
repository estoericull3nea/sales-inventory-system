<?php
require '../../connection.php';

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Check if data is properly received
if (isset($data['id']) && isset($data['name']) && isset($data['address']) && isset($data['contact1'])) {
    $id = intval($data['id']);
    $name = trim($data['name']);
    $address = trim($data['address']);
    $contact1 = trim($data['contact1']);

    // Validate data
    if (strlen($name) >= 3 && strlen($name) <= 200 && strlen($address) <= 500 && strlen($contact1) <= 20) {
        // Prepare SQL statement
        $stmt = $conn->prepare("UPDATE supplier_details SET supplier_name = ?, supplier_address = ?, supplier_contact1 = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $address, $contact1, $id);

        // Execute the query
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Supplier updated successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update supplier"]);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input data"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}

// Close the database connection
$conn->close();
