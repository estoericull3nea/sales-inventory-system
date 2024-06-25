<?php
require '../../connection.php';

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Validate the data
if (isset($data['stock_name'], $data['stock_quantity'], $data['supplier_name'], $data['price'], $data['category'])) {
    $stock_name = $data['stock_name'];
    $stock_quantity = $data['stock_quantity'];
    $supplier_name = $data['supplier_name'];
    $price = $data['price'];
    $category = $data['category'];

    // Prepare the SQL statement to insert the new product
    $sql = "INSERT INTO products (stock_name, stock_quantity, supplier_name, price, category) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(["error" => "Failed to prepare the SQL statement"]);
        exit;
    }

    $stmt->bind_param("sisis", $stock_name, $stock_quantity, $supplier_name, $price, $category);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo json_encode(["error" => "Failed to add product"]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Invalid data
    echo json_encode(["error" => "Invalid data"]);
}

// Close the database connection
$conn->close();
