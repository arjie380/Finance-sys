<?php
include('header.php');
include('functions.php');
include('db.php'); // Database connection

// Fetch the payroll data if ID is provided in the URL
if (isset($_GET['id'])) {
    $payroll_id = $_GET['id'];
    // Fetch payroll data
    $stmt = $conn->prepare("SELECT * FROM payrolls WHERE id = :id");
    $stmt->bindValue(':id', $payroll_id, PDO::PARAM_INT);
    $stmt->execute();
    $payroll = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$payroll) {
        die('Payroll not found.');
    }
}

// Check if the form is submitted to update the payroll
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_payroll') {
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
        $net_pay = $_POST['net_pay'];
        $notes = $_POST['notes'];

        // Prepare and execute the query
        $stmt = $conn->prepare("UPDATE payrolls SET 
                                payroll_date = :payroll_date, 
                                employee_name = :employee_name, 
                                employee_department = :employee_department, 
                                employee_position = :employee_position, 
                                basic_pay = :basic_pay, 
                                bonuses = :bonuses, 
                                deductions = :deductions, 
                                tax_rate = :tax_rate, 
                                net_pay = :net_pay, 
                                notes = :notes 
                                WHERE id = :id");
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
        $stmt->bindValue(':id', $payroll_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Success: Show a success message and redirect to payroll list page
            echo "<script>alert('Payroll successfully updated.'); window.location.href = 'payroll-list.php';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating payroll. Please try again.');</script>";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Payroll</title>
</head>
<body>

<h2>Edit Payroll</h2>
<form action="payroll-edit.php?id=<?php echo $payroll_id; ?>" method="POST">
    <input type="hidden" name="action" value="update_payroll">
    
    <label for="payroll_date">Payroll Date:</label>
    <input type="date" name="payroll_date" value="<?php echo $payroll['payroll_date']; ?>" required><br><br>

    <label for="employee_name">Employee Name:</label>
    <input type="text" name="employee_name" value="<?php echo $payroll['employee_name']; ?>" required><br><br>

    <label for="employee_department">Department:</label>
    <input type="text" name="employee_department" value="<?php echo $payroll['employee_department']; ?>" required><br><br>

    <label for="employee_position">Position:</label>
    <input type="text" name="employee_position" value="<?php echo $payroll['employee_position']; ?>" required><br><br>

    <label for="basic_pay">Basic Pay:</label>
    <input type="number" name="basic_pay" step="0.01" value="<?php echo $payroll['basic_pay']; ?>" required><br><br>

    <label for="bonuses">Bonuses:</label>
    <input type="number" name="bonuses" step="0.01" value="<?php echo $payroll['bonuses']; ?>"><br><br>

    <label for="deductions">Deductions:</label>
    <input type="number" name="deductions" step="0.01" value="<?php echo $payroll['deductions']; ?>"><br><br>

    <label for="tax_rate">Tax Rate (%):</label>
    <input type="number" name="tax_rate" step="0.01" value="<?php echo $payroll['tax_rate']; ?>"><br><br>

    <label for="net_pay">Net Pay:</label>
    <input type="number" name="net_pay" step="0.01" value="<?php echo $payroll['net_pay']; ?>" required><br><br>

    <label for="notes">Notes:</label>
    <textarea name="notes"><?php echo $payroll['notes']; ?></textarea><br><br>

    <input type="submit" value="Update Payroll">
</form>

</body>
</html>

