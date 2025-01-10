<?php
include('header.php');
include('functions.php');
include('db.php'); 


$stmt = $conn->prepare("SELECT * FROM payrolls ORDER BY payroll_date DESC");
$stmt->execute();
$payrolls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payroll List</title>
</head>
<body>

<h2>Payroll List</h2>
<table border="1">
    <tr>
        <th>Payroll Date</th>
        <th>Employee Name</th>
        <th>Department</th>
        <th>Position</th>
        <th>Basic Pay</th>
        <th>Bonuses</th>
        <th>Deductions</th>
        <th>Tax Rate (%)</th>
        <th>Net Pay</th>
        <th>Notes</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($payrolls as $payroll): ?>
        <tr>
            <td><?php echo htmlspecialchars($payroll['payroll_date']); ?></td>
            <td><?php echo htmlspecialchars($payroll['employee_name']); ?></td>
            <td><?php echo htmlspecialchars($payroll['employee_department']); ?></td>
            <td><?php echo htmlspecialchars($payroll['employee_position']); ?></td>
            <td><?php echo number_format($payroll['basic_pay'], 2); ?></td>
            <td><?php echo number_format($payroll['bonuses'], 2); ?></td>
            <td><?php echo number_format($payroll['deductions'], 2); ?></td>
            <td><?php echo htmlspecialchars($payroll['tax_rate']); ?>%</td>
            <td><?php echo number_format($payroll['net_pay'], 2); ?></td>
            <td><?php echo htmlspecialchars($payroll['notes']); ?></td>
            <td><a href="payroll-edit.php?id=<?php echo $payroll['id']; ?>">Edit</a> | <a href="payroll-delete.php?id=<?php echo $payroll['id']; ?>">Delete</a></td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
