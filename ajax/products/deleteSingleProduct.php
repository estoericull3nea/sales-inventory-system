<?php
require '../../connection.php';

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Check if the id is set and is valid
if (isset($data['id']) && is_numeric($data['id'])) {
    $id = $data['id'];

    // Prepare the SQL statement to delete the product
    $sql = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare the SQL statement"]);
        exit;
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo json_encode(["error" => "No product found with the provided id"]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Invalid id
    echo json_encode(["error" => "Invalid id"]);
}

// Close the database connection
$conn->close();
