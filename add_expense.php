<?php
// Include the database connection file
include 'db.php';

// Read the raw input from the request
$rawInput = file_get_contents('php://input');

// Log the raw input for debugging purposes
file_put_contents('log.txt', "Raw Input: " . $rawInput . "\n", FILE_APPEND);

// Decode the JSON input
$data = json_decode($rawInput, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'JSON decoding error: ' . json_last_error_msg()]);
    exit;
}

// Validate the input fields
$category = $data['category'] ?? null;
$amount = $data['amount'] ?? null;
$date = $data['date'] ?? null;

if (empty($category) || empty($amount) || empty($date)) {
    echo json_encode(['error' => 'All fields (category, amount, date) are required.']);
    exit;
}

try {
    // Prepare the SQL query
    $sql = "INSERT INTO expenses (category, amount, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Execute the query with the provided data
    $stmt->execute([$category, $amount, $date]);

    // Send a success response
    echo json_encode(['message' => 'Expense added successfully!']);
} catch (PDOException $e) {
    // Log the error for debugging
    file_put_contents('log.txt', "Database Error: " . $e->getMessage() . "\n", FILE_APPEND);

    // Send an error response
    echo json_encode(['error' => 'Failed to add expense: ' . $e->getMessage()]);
}
?>
