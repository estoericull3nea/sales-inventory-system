<?php
require '../../connection.php';

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Check if the required fields are present
if (isset($data['name']) && isset($data['address']) && isset($data['contact'])) {
    $name = $data['name'];
    $address = $data['address'];
    $contact = $data['contact'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO supplier_details (supplier_name, supplier_address, supplier_contact1) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $address, $contact);

    // Execute the statement
    if ($stmt->execute()) {
        echo '1';
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add supplier."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
}
