<?php
include('header.php');
include('db.php');


$sql = "SELECT DISTINCT item_number, products FROM sales_invoices";
$stmt = $conn->prepare($sql);
$stmt->execute();
$existing_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql = "SELECT customer_id, customer_name, products, qty, unit_price, sales_tax, payment_status 
        FROM sales_invoices 
        ORDER BY id DESC 
        LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$latest_invoice = $stmt->fetch(PDO::FETCH_ASSOC);


$sql = "SELECT MAX(CAST(customer_id AS UNSIGNED)) FROM sales_invoices";
$stmt = $conn->prepare($sql);
$stmt->execute();
$last_customer_id = $stmt->fetchColumn();
$next_customer_id = str_pad($last_customer_id + 1, 3, '0', STR_PAD_LEFT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $customer_name = $_POST['customer_name'];
    $items = $_POST['items'];
    $qty = $_POST['qty'];
    $unit_price = $_POST['unit_price'];
    $sales_tax = $_POST['sales_tax'];
    $payment_status = $_POST['payment_status'];

    $total_amount = 0;
    foreach ($items as $index => $product) {
        $total_amount += ($qty[$index] * $unit_price[$index]) + $sales_tax[$index];
    }

    if (empty($customer_id) || empty($customer_name) || empty($items) || empty($qty) || empty($unit_price) || empty($sales_tax)) {
        $error_message = "All fields are required.";
    } else {
        // Insert the invoice into the database for each item
        $sql = "INSERT INTO sales_invoices (customer_id, customer_name, products, qty, unit_price, total_amount, sales_tax, payment_status, invoice_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($items as $index => $product) {
            $stmt->execute([
                $customer_id,
                $customer_name,
                $product,
                $qty[$index],
                $unit_price[$index],
                ($qty[$index] * $unit_price[$index]) + $sales_tax[$index],
                $sales_tax[$index],
                $payment_status,
                $next_customer_id
            ]);
        }
        $success_message = "Invoice added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Invoice Form</title>
    <style>
        .form-row {
            display: flex;
            justify-content: space-between;
        }
        .form-row .form-group {
            width: 48%;
        }
        #new-item {
            display: none;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Add Invoice</h1>
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (!empty($error_message)) : ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        <form method="POST">
            <table class="table">
                <tr>
                    <td><label>Customer ID:</label></td>
                    <td><input type="text" name="customer_id" class="form-control" value="<?= $next_customer_id ?>" readonly></td>
                </tr>
                <tr>
                    <td><label>Customer Name:</label></td>
                    <td><input type="text" name="customer_name" class="form-control" required></td>
                </tr>
                <tr>
                    <td><label>Payment Status:</label></td>
                    <td>
                        <select name="payment_status" class="form-select">
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <div id="items-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="products">Products:</label>
                        <textarea name="items[]" class="form-control" required placeholder="Enter product details"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="qty">Quantity:</label>
                        <input type="number" name="qty[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="unit_price">Unit Price:</label>
                        <input type="number" step="0.01" name="unit_price[]" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="sales_tax">Sales Tax:</label>
                        <input type="number" step="0.01" name="sales_tax[]" class="form-control" required>
                    </div>
                </div>
            </div>

            <small><a href="javascript:void(0)" onclick="addNewItem()">Add a new item</a></small>

            <button type="submit" class="btn btn-primary mt-3">Add Invoice</button>
        </form>

        <?php if ($latest_invoice): ?>
            <h2 class="mt-4">Latest Customer Invoice Details</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Customer ID</th>
                        <th>Customer Name</th>
                        <th>Products</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Sales Tax</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($latest_invoice['customer_id']) ?></td>
                        <td><?= htmlspecialchars($latest_invoice['customer_name']) ?></td>
                        <td><?= htmlspecialchars($latest_invoice['products']) ?></td>
                        <td><?= htmlspecialchars($latest_invoice['qty']) ?></td>
                        <td>₱<?= number_format($latest_invoice['unit_price'], 2) ?></td>
                        <td>₱<?= number_format($latest_invoice['sales_tax'], 2) ?></td>
                        <td><?= htmlspecialchars($latest_invoice['payment_status']) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
        function addNewItem() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.classList.add('form-row');
            newRow.innerHTML = `
                <div class="form-group">
                    <label for="products">Products:</label>
                    <textarea name="items[]" class="form-control" required placeholder="Enter product details"></textarea>
                </div>
                <div class="form-group">
                    <label for="qty">Quantity:</label>
                    <input type="number" name="qty[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="unit_price">Unit Price:</label>
                    <input type="number" step="0.01" name="unit_price[]" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="sales_tax">Sales Tax:</label>
                    <input type="number" step="0.01" name="sales_tax[]" class="form-control" required>
                </div>
            `;
            container.appendChild(newRow);
        }
    </script>
</body>
</html>
