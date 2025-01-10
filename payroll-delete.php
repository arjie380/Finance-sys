<?php
include('header.php');
include('functions.php');
include('db.php'); // Database connection

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $payroll_id = $_GET['id'];

    try {
        // Prepare and execute the delete query
        $stmt = $conn->prepare("DELETE FROM payrolls WHERE id = :id");
        $stmt->bindValue(':id', $payroll_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Success: Show a success message and redirect to payroll list page
            echo "<script>alert('Payroll successfully deleted.'); window.location.href = 'payroll-list.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error deleting payroll. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "<script>alert('Payroll ID is missing.'); window.location.href = 'payroll-list.php';</script>";
    exit();
}
?>