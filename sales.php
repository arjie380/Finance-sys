<?php
include('header.php');
include('db.php');


$customer_id = null;


if (isset($_POST['search'])) {
    $customer_id = $_POST['customer_id'];


    $sql = "SELECT * FROM sales_invoices WHERE customer_id = :customer_id ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {

    $sql = "SELECT * FROM sales_invoices ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Invoices</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-top: 20px;
        }

        .table-container {
            width: 95%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e1f7e1;
        }

        .action-btn {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        button {
            padding: 5px 10px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .search-container {
            margin: 20px;
            text-align: center;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<!-- Search Form -->
<div class="search-container">
    <form method="POST" action="">
        <input type="text" name="customer_id" placeholder="Enter Customer ID to search" value="<?= ($customer_id) ?>" required>
        <input type="submit" name="search" value="Search">
    </form>
</div>

<div class="table-container">
    <?php if (!empty($invoices)): ?>
        <h1>Sales Invoices</h1>
        <table>
            <thead>
                <tr>
                    <th>Customer ID</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Products</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Line Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($invoices as $invoice): ?>
                    <tr>
                        <td><?= htmlspecialchars($invoice['customer_id']) ?></td>
                        <td><?= htmlspecialchars($invoice['date']) ?></td>
                        <td><?= htmlspecialchars($invoice['customer_name']) ?></td>
                        <td><?= htmlspecialchars($invoice['products']) ?></td>
                        <td><?= htmlspecialchars($invoice['qty']) ?></td>
                        <td>₱<?= number_format($invoice['unit_price'], 2) ?></td>
                        <td>₱<?= number_format($invoice['total_amount'], 2) ?></td>
                        <td>
                            <div class="action-btn">
                                <form action="generate_invoice.php" method="POST">
                                    <input type="hidden" name="customer_id" value="<?= htmlspecialchars($invoice['customer_id']) ?>">
                                    <button type="submit">Download Invoice</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h1>No Sales Invoices Found</h1>
    <?php endif; ?>
</div>

</body>
</html>
