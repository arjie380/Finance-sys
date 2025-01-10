<?php
include('header.php');
include('db.php');

$query = "SELECT * FROM payroll";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payroll List</title>
  <style>

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    th, td {
      padding: 10px;
      text-align: left;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f2f2f2;
    }


    .btn-download {
      display: inline-block;
      padding: 8px 16px;
      background-color: #4CAF50;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .btn-download:hover {
      background-color: #45a049;
    }

    .btn-download:active {
      background-color: #3e8e41;
    }

  
    .table-container {
      margin: 20px;
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body>

  <div class="table-container">
    <?php
    if ($result->num_rows > 0) {
      echo "<h1>Payroll List</h1>";
      echo '<table class="table table-striped table-bordered">';
      echo "<thead>
              <tr>
                <th>Department</th>
                <th>Date</th>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Total Income</th>
                <th>Total Deductions</th>
                <th>Net Pay</th>
                <th>Actions</th>
              </tr>
            </thead>";
      echo "<tbody>";

      while ($row = $result->fetch_assoc()) {
        $total_income = $row['total_income'];
        $total_deductions = $row['total_deductions'];
        $net_pay = $row['net_pay'];

        echo "<tr>
                <td>" . $row['department'] . "</td>
                <td>" . $row['date'] . "</td>
                <td>" . $row['employee_name'] . "</td>
                <td>" . $row['position'] . "</td>
                <td>₱" . number_format($total_income, 2) . "</td>
                <td>₱" . number_format($total_deductions, 2) . "</td>
                <td>₱" . number_format($net_pay, 2) . "</td>
                <td><a href='generate-invoice.php?employee_name=" . urlencode($row['employee_name']) . "' class='btn-download'>Download Invoice</a></td>
              </tr>";
      }

      echo "</tbody>";
      echo "</table>";
    } else {
      echo "No payroll data found.";
    }
    ?>
  </div>

</body>
</html>
