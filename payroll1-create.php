<?php
ob_start(); 
include('header.php');
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'];
    $date = $_POST['date'];
    $position = $_POST['position'];
    $employeeName = $_POST['employee_name'];
    $basicPayRate = $_POST['basic_pay_rate'];
    $basicPayHours = $_POST['basic_pay_hours'];
    $basicPayDeduction = $_POST['basic_pay_deduction'];
    $overtimeRate = $_POST['overtime_rate'];
    $overtimeHours = $_POST['overtime_hours'];
    $overtimeDeduction = $_POST['overtime_deduction'];
    $holidayRate = $_POST['holiday_rate'];
    $holidayHours = $_POST['holiday_hours'];
    $holidayDeduction = $_POST['holiday_deduction'];
    $sickRate = $_POST['sick_rate'];
    $sickHours = $_POST['sick_hours'];
    $sickDeduction = $_POST['sick_deduction'];
    $totalIncome = $_POST['total_income'];  
    $totalDeductions = $_POST['total_deductions'];  
    $netPay = $_POST['net_pay'];

    $query = "INSERT INTO payroll (department, date, position, employee_name, basic_pay_rate, basic_pay_hours, basic_pay_deduction, 
    overtime_rate, overtime_hours, overtime_deduction, holiday_rate, holiday_hours, holiday_deduction, 
    sick_rate, sick_hours, sick_deduction, total_income, total_deductions, net_pay) 
    VALUES (:department, :date, :position, :employee_name, :basic_pay_rate, :basic_pay_hours, :basic_pay_deduction, 
    :overtime_rate, :overtime_hours, :overtime_deduction, :holiday_rate, :holiday_hours, :holiday_deduction, 
    :sick_rate, :sick_hours, :sick_deduction, :total_income, :total_deductions, :net_pay)";

    $stmt = $conn->prepare($query);

  
    $stmt->bindValue(':department', $department);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':position', $position);
    $stmt->bindValue(':employee_name', $employeeName);
    $stmt->bindValue(':basic_pay_rate', $basicPayRate, PDO::PARAM_STR);
    $stmt->bindValue(':basic_pay_hours', $basicPayHours, PDO::PARAM_STR);
    $stmt->bindValue(':basic_pay_deduction', $basicPayDeduction, PDO::PARAM_STR);
    $stmt->bindValue(':overtime_rate', $overtimeRate, PDO::PARAM_STR);
    $stmt->bindValue(':overtime_hours', $overtimeHours, PDO::PARAM_STR);
    $stmt->bindValue(':overtime_deduction', $overtimeDeduction, PDO::PARAM_STR);
    $stmt->bindValue(':holiday_rate', $holidayRate, PDO::PARAM_STR);
    $stmt->bindValue(':holiday_hours', $holidayHours, PDO::PARAM_STR);
    $stmt->bindValue(':holiday_deduction', $holidayDeduction, PDO::PARAM_STR);
    $stmt->bindValue(':sick_rate', $sickRate, PDO::PARAM_STR);
    $stmt->bindValue(':sick_hours', $sickHours, PDO::PARAM_STR);
    $stmt->bindValue(':sick_deduction', $sickDeduction, PDO::PARAM_STR);
    $stmt->bindValue(':total_income', $totalIncome, PDO::PARAM_STR);
    $stmt->bindValue(':total_deductions', $totalDeductions, PDO::PARAM_STR);
    $stmt->bindValue(':net_pay', $netPay, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header("Location: payroll1-create.php?success=true");
        exit();
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    $stmt->close();
    $conn = null;
}

ob_end_flush();
?>

<?php
if (isset($_GET['success']) && $_GET['success'] == 'true') {
    echo "<div class='alert alert-success' role='alert'>
            <strong>Success!</strong> Payroll successfully created.
          </div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3Ln5/3H6eaRp4KnDcn60XrJ6jZcDeVp3DxoHoo1r6nnpqtm8n8HNBKk24" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-cn7l7gDp0eyw02+f0tK0pXtKvmzktB/0JrZznmlwM7ZoNrr9ge58YnoZfmlHIe9S" crossorigin="anonymous"></script>
  <title>Payroll Template</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
    }
    .payroll-container {
      max-width: 800px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      padding: 20px;
    }
    .payroll-header {
      text-align: center;
      background: #2d6a4f;
      color: white;
      padding: 10px;
      border-radius: 6px;
      font-size: 24px;
      font-weight: bold;
    }
    .payroll-details {
      margin: 20px 0;
    }
    .payroll-details label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
    }
    .payroll-details input {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .payroll-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    .payroll-table th, .payroll-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    .payroll-table th {
      background-color: #2d6a4f;
      color: white;
    }
    .summary {
      text-align: right;
      margin-top: 20px;
    }
    .summary span {
      font-weight: bold;
    }
  </style>
  
</head>
<body>
  <div class="payroll-container">
  <h1 class="payroll-header">Monthly Payroll</h1>

  <form action="payroll1-create.php" method="POST">
    <!-- Employee Details -->
    <fieldset class="payroll-details">
      <legend>Employee Details</legend>
      <label for="department">Department:</label>
      <input type="text" id="department" name="department" placeholder="Enter Department">
      <label for="date">Date:</label>
      <input type="date" id="date" name="date">
      <label for="position">Position:</label>
      <input type="text" id="position" name="position" placeholder="Enter Position">
      <label for="employee-name">Employee's Name:</label>
      <input type="text" id="employee-name" name="employee_name" placeholder="Enter Employee Name">
    </fieldset>

    <!-- Income and Deductions Table -->
    <fieldset>
      <legend>Income and Deductions</legend>
      <table class="payroll-table">
        <thead>
          <tr>
            <th>Income Description</th>
            <th>Rate</th>
            <th>Total Hours</th>
            <th>Deductions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Basic Pay</td>
            <td><input type="number" id="basic-pay-rate" name="basic_pay_rate" placeholder="Enter Rate" step="0.01"></td>
            <td><input type="number" id="basic-pay-hours" name="basic_pay_hours" placeholder="Enter Hours" step="1"></td>
            <td><input type="number" id="basic-pay-deduction" name="basic_pay_deduction" placeholder="Enter Deduction" step="0.01"></td>
          </tr>
          <tr>
            <td>Overtime Pay</td>
            <td><input type="number" id="overtime-rate" name="overtime_rate" placeholder="Enter Rate" step="0.01"></td>
            <td><input type="number" id="overtime-hours" name="overtime_hours" placeholder="Enter Hours" step="1"></td>
            <td><input type="number" id="overtime-deduction" name="overtime_deduction" placeholder="Enter Deduction" step="0.01"></td>
          </tr>
          <tr>
            <td>Holiday Pay</td>
            <td><input type="number" id="holiday-rate" name="holiday_rate" placeholder="Enter Rate" step="0.01"></td>
            <td><input type="number" id="holiday-hours" name="holiday_hours" placeholder="Enter Hours" step="1"></td>
            <td><input type="number" id="holiday-deduction" name="holiday_deduction" placeholder="Enter Deduction" step="0.01"></td>
          </tr>
          <tr>
            <td>Sick Pay</td>
            <td><input type="number" id="sick-rate" name="sick_rate" placeholder="Enter Rate" step="0.01"></td>
            <td><input type="number" id="sick-hours" name="sick_hours" placeholder="Enter Hours" step="1"></td>
            <td><input type="number" id="sick-deduction" name="sick_deduction" placeholder="Enter Deduction" step="0.01"></td>
          </tr>
        </tbody>
      </table>
    </fieldset>

    <!-- Hidden fields for totals -->
    <input type="hidden" name="total_income" id="total_income">
    <input type="hidden" name="total_deductions" id="total_deductions">
    <input type="hidden" name="net_pay" id="net_pay">

    <!-- Submit Button -->
    <button type="submit" class="btn btn-success" onclick="calculatePayroll()">Submit Payroll</button>
  </form>
  <button class="btn btn-primary" style="position: relative; left:140px; bottom: 33px; " onclick="redirectToPayroll()">View List</button>
  </div>

  <script>
    function calculatePayroll() {
      var basicPayRate = parseFloat(document.getElementById('basic-pay-rate').value) || 0;
      var basicPayHours = parseFloat(document.getElementById('basic-pay-hours').value) || 0;
      var basicPayDeduction = parseFloat(document.getElementById('basic-pay-deduction').value) || 0;

      var overtimeRate = parseFloat(document.getElementById('overtime-rate').value) || 0;
      var overtimeHours = parseFloat(document.getElementById('overtime-hours').value) || 0;
      var overtimeDeduction = parseFloat(document.getElementById('overtime-deduction').value) || 0;

      var holidayRate = parseFloat(document.getElementById('holiday-rate').value) || 0;
      var holidayHours = parseFloat(document.getElementById('holiday-hours').value) || 0;
      var holidayDeduction = parseFloat(document.getElementById('holiday-deduction').value) || 0;

      var sickRate = parseFloat(document.getElementById('sick-rate').value) || 0;
      var sickHours = parseFloat(document.getElementById('sick-hours').value) || 0;
      var sickDeduction = parseFloat(document.getElementById('sick-deduction').value) || 0;

      var totalIncome = basicPayRate * basicPayHours + overtimeRate * overtimeHours + holidayRate * holidayHours + sickRate * sickHours;
      var totalDeductions = basicPayDeduction + overtimeDeduction + holidayDeduction + sickDeduction;
      var netPay = totalIncome - totalDeductions;

      document.getElementById('total_income').value = totalIncome.toFixed(2);
      document.getElementById('total_deductions').value = totalDeductions.toFixed(2);
      document.getElementById('net_pay').value = netPay.toFixed(2);
    }

  </script>
  <script>
     function redirectToPayroll() {
      window.location.href = 'payroll1-list.php';
    }
  </script>
</body>
</html>
