<?php
include('header.php');
include('functions.php');
include('db.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $category = $_POST['category'] ?? '';
    $amount = $_POST['amount'] ?? '';
    $date = $_POST['date'] ?? '';

    if (empty($category) || empty($amount) || empty($date)) {
        $error_message = "All fields are required.";
    } else {
     
        try {
            $sql = "INSERT INTO expenses (department, amount, date, status) VALUES (?, ?, ?, 'Pending')";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$category, $amount, $date]);

            $success_message = "Expense added successfully!";
        } catch (PDOException $e) {
            $error_message = "Error adding expense: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJw4BvY8J4L+6n78e5Jvi5hPYs12rZar7f1yFvDwsrhMO6YwFOZaGxpJl8fG" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Expense Tracker</title>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Expense Tracker</h1>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="input-section mb-4">
            <form method="POST">
                <label for="category-select">Department:</label>
                <select id="category-select" name="category" class="form-select mb-2">
                    <option value="">Select Department</option>
                    <option value="HR" <?= isset($category) && $category == 'HR' ? 'selected' : '' ?>>HR</option>
                    <option value="Marketing" <?= isset($category) && $category == 'Marketing' ? 'selected' : '' ?>>Marketing</option>
                    <option value="Operations" <?= isset($category) && $category == 'Operations' ? 'selected' : '' ?>>Operations</option>
                    <option value="IT" <?= isset($category) && $category == 'IT' ? 'selected' : '' ?>>IT</option>
                    <option value="Finance" <?= isset($category) && $category == 'Finance' ? 'selected' : '' ?>>Finance</option>
                </select>

                <label for="amount-input">Amount:</label>
                <input type="number" name="amount" id="amount-input" class="form-control mb-2" placeholder="Enter amount" value="<?= isset($amount) ? $amount : '' ?>">

                <label for="date-input">Date:</label>
                <input type="date" name="date" id="date-input" class="form-control mb-3" value="<?= isset($date) ? $date : '' ?>">

                <button type="submit" class="btn btn-primary">Add Expense</button>
                <button type="button" id="view-expenses-btn" class="btn" style="background-color: #28a745; border-color: #28a745; color: white; font-size: large;" onmouseover="this.style.backgroundColor='#218838'; this.style.borderColor='#1e7e34';" onmouseout="this.style.backgroundColor='#28a745'; this.style.borderColor='#28a745';">View Expenses</button>
            </form>
        </div>

        <script>
            document.getElementById('view-expenses-btn').addEventListener('click', (event) => {
                event.preventDefault();  
                window.location.href = 'expenses.php';  
            });
        </script>
    </div>
</body>
</html>
