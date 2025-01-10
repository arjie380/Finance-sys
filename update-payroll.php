<?php
include('header.php');
include('functions.php');


if (!isset($_SESSION['user_logged_in'])) {
    echo "Please log in to update payroll.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    a
    $id = $_POST['id'];
    $employee_name = $_POST['employee_name'];
    $employee_position = $_POST['employee_position'];
    $payroll_date = $_POST['payroll_date'];
    $basic_pay = $_POST['basic_pay'];
    $bonuses = $_POST['bonuses'];
    $deductions = $_POST['deductions'];
    $tax_rate = $_POST['tax_rate'];

   
    $gross_pay = $basic_pay + $bonuses;
    $tax = ($tax_rate / 100) * $gross_pay;
    $net_pay = $gross_pay - $tax - $deductions;

    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "invoicemgsys";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

 
    $sql = "UPDATE payrolls SET employee_name = ?, employee_position = ?, payroll_date = ?, basic_pay = ?, bonuses = ?, deductions = ?, tax_rate = ?, net_pay = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $employee_name, $employee_position, $payroll_date, $basic_pay, $bonuses, $deductions, $tax_rate, $net_pay, $id);

    if ($stmt->execute()) {
        echo "Payroll updated successfully!";
    } else {
        echo "Error updating payroll: " . $stmt->error;
    }


    $conn->close();
}

include('footer.php');
?>
