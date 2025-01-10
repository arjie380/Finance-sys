<?php
include('header.php');
include('db.php');

// Check if 'employee_name' is set in the URL
if (isset($_GET['employee_name'])) {
    $employee_name = $_GET['employee_name'];

    // Query to fetch payroll data for the employee
    $query = "SELECT * FROM payroll WHERE employee_name = '$employee_name'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Prepare invoice details
        $department = $row['department'];
        $date = $row['date'];
        $position = $row['position'];
        $total_income = $row['total_income'];
        $total_deductions = $row['total_deductions'];
        $net_pay = $row['net_pay'];

        // Prepare the invoice content to display as plain text
        $invoice_content = "
Invoice for Employee: $employee_name

Department: $department
Date: $date
Position: $position

Total Income: ₱" . number_format($total_income, 2) . "
Total Deductions: ₱" . number_format($total_deductions, 2) . "
Net Pay: ₱" . number_format($net_pay, 2) . "

Thank you for your service.
";

        // Display the invoice content
        echo "<pre>$invoice_content</pre>";

    } else {
        echo "No payroll data found for this employee.";
    }
} else {
    echo "Employee name not provided.";
}
?>
