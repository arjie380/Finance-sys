<?php
include('db.php');

// Ensure the necessary query parameters are provided
if (isset($_GET['id']) && isset($_GET['action'])) {
    $expenseId = $_GET['id'];
    $action = $_GET['action'];

    // Determine the new status based on the action
    if ($action == 'approve') {
        $newStatus = 'Approved';
    } elseif ($action == 'reject') {
        $newStatus = 'Rejected';
    } else {
        // Invalid action, redirect back to expenses page
        header('Location: expenses.php');
        exit;
    }

    try {
        // Update the status of the expense in the database
        $sql = "UPDATE expenses SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newStatus, $expenseId]);

        // Redirect back to the expenses list after updating
        header('Location: expenses.php');
        exit;
    } catch (PDOException $e) {
        // Handle any errors (optional: display an error message)
        echo "Error updating expense status: " . $e->getMessage();
        exit;
    }
} else {
    // Missing parameters, redirect to expenses page
    header('Location: expenses.php');
    exit;
}
