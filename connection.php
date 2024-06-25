<?php
// Define connection parameters
$servername = "localhost"; // Server name (e.g., 'localhost' or an IP address)
$username = "root"; // Username for the MySQL database
$password = ""; // Password for the MySQL database
$dbname = "posnic"; // Name of the database

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
