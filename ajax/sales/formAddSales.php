<?php
require '../../connection.php';

// Read the raw POST data
$rawData = file_get_contents("php://input");

// Decode the JSON data
$data = json_decode($rawData, true);

// Sanitize and validate inputs
$stock_name = filter_var($data['selectedProduct'], FILTER_SANITIZE_STRING);
$product_id = filter_var($data['productId'], FILTER_VALIDATE_INT); // Added to get product ID
$quantity = filter_var($data['quantity'], FILTER_VALIDATE_INT);
$selling_price = filter_var($data['price'], FILTER_VALIDATE_FLOAT);
$username = filter_var($data['supplier'], FILTER_SANITIZE_STRING);
$total = filter_var($data['total'], FILTER_VALIDATE_FLOAT);
$payment = filter_var($data['payment'], FILTER_VALIDATE_FLOAT);
$address = filter_var($data['address'], FILTER_SANITIZE_STRING);
$contact = filter_var($data['contact'], FILTER_SANITIZE_STRING);

// Check if required fields are valid
if ($product_id && $stock_name && $quantity !== false && $selling_price !== false && $total !== false && $payment !== false && $payment > 0 && $payment <= $total && $address && $contact) {
    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare an SQL statement for inserting the sales record
        $stmt = $conn->prepare("INSERT INTO stock_entries (stock_name, quantity, selling_price, username, total, address, contact, payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sidsdssd", $stock_name, $quantity, $selling_price, $username, $total, $address, $contact, $payment);

        // Execute the insert statement
        if ($stmt->execute()) {
            // Prepare an SQL statement for updating the stock quantity
            $stmt = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
            $stmt->bind_param("ii", $quantity, $product_id); // Updated to decrease the stock quantity

            // Execute the update statement
            if ($stmt->execute()) {
                // Commit the transaction
                $conn->commit();
                echo json_encode(["status" => "success", "message" => "Data inserted and stock updated successfully"]);
            } else {
                // Rollback the transaction if the update fails
                $conn->rollback();
                echo json_encode(["status" => "error", "message" => "Error updating stock"]);
            }
        } else {
            // Rollback the transaction if the insert fails
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => "Error inserting data"]);
        }

        // Close the statement
        $stmt->close();
    } catch (Exception $e) {
        // Rollback the transaction in case of an exception
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => "Transaction failed: " . $conn->error]);
    }
} else {
    // Validation error response
    echo json_encode(["status" => "error", "message" => "Invalid input data"]);
}

// Close the database connection
$conn->close();
