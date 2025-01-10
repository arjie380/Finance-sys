<?php
include('header.php');
include('functions.php');
include('db.php'); // Database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'create_payroll') {
    try {
        // Sanitize and retrieve form data
        $payroll_date = $_POST['payroll_date'];
        $employee_name = $_POST['employee_name'];
        $employee_department = $_POST['employee_department'];
        $employee_position = $_POST['employee_position'];
        $basic_pay = $_POST['basic_pay'];
        $bonuses = $_POST['bonuses'];
        $deductions = $_POST['deductions'];
        $tax_rate = $_POST['tax_rate'];
        $notes = $_POST['notes'];

        // Calculate total deductions
        $total_deductions = $deductions + ($basic_pay * ($tax_rate / 100));

        // Calculate net pay
        $net_pay = ($basic_pay + $bonuses) - $total_deductions;

        // Prepare and execute the query
        $stmt = $conn->prepare("INSERT INTO payrolls (payroll_date, employee_name, employee_department, employee_position, basic_pay, bonuses, deductions, tax_rate, net_pay, notes) 
                                VALUES (:payroll_date, :employee_name, :employee_department, :employee_position, :basic_pay, :bonuses, :deductions, :tax_rate, :net_pay, :notes)");
        $stmt->bindValue(':payroll_date', $payroll_date, PDO::PARAM_STR);
        $stmt->bindValue(':employee_name', $employee_name, PDO::PARAM_STR);
        $stmt->bindValue(':employee_department', $employee_department, PDO::PARAM_STR);
        $stmt->bindValue(':employee_position', $employee_position, PDO::PARAM_STR);
        $stmt->bindValue(':basic_pay', $basic_pay, PDO::PARAM_STR);
        $stmt->bindValue(':bonuses', $bonuses, PDO::PARAM_STR);
        $stmt->bindValue(':deductions', $deductions, PDO::PARAM_STR);
        $stmt->bindValue(':tax_rate', $tax_rate, PDO::PARAM_STR);
        $stmt->bindValue(':net_pay', $net_pay, PDO::PARAM_STR);
        $stmt->bindValue(':notes', $notes, PDO::PARAM_STR);

        if ($stmt->execute()) {
            // Success: Redirect to payroll list page with success message
            echo "<script>alert('Payroll successfully created.'); window.location.href = 'payroll-list.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error creating payroll. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Payroll</title>
</head>
<body>

<h2>Create Payroll</h2>
<form action="payroll-create.php" method="POST">
    <input type="hidden" name="action" value="create_payroll">
    
    <label for="payroll_date">Payroll Date:</label>
    <input type="date" name="payroll_date" required><br><br>

    <label for="employee_name">Employee Name:</label>
    <input type="text" name="employee_name" required><br><br>

    <label for="employee_department">Department:</label>
    <input type="text" name="employee_department" required><br><br>

    <label for="employee_position">Position:</label>
    <input type="text" name="employee_position" required><br><br>

    <label for="basic_pay">Basic Pay:</label>
    <input type="number" name="basic_pay" step="0.01" required><br><br>

    <label for="bonuses">Bonuses:</label>
    <input type="number" name="bonuses" step="0.01"><br><br>

    <label for="deductions">Deductions:</label>
    <input type="number" name="deductions" step="0.01"><br><br>

    <label for="tax_rate">Tax Rate (%):</label>
    <input type="number" name="tax_rate" step="0.01"><br><br>

    <label for="notes">Notes:</label>
    <textarea name="notes"></textarea><br><br>

    <input type="submit" value="Create Payroll">
</form>

</body>
</html>
