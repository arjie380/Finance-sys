<?php
include('db.php');

// Get the customer_id from the POST request
$customer_id = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;

if ($customer_id) {
    // Fetch invoices for the given customer
    $sql = "SELECT * FROM sales_invoices WHERE customer_id = :customer_id ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if there are invoices for the given customer
    if (!empty($invoices)) {
        // Start output buffering to capture the HTML content
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sales Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                }
                .invoice-container {
                    width: 60%;
                    margin: 0 auto;
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                }
                .invoice-header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #ddd;
                    padding-bottom: 10px;
                }
                .invoice-header h1 {
                    margin: 0;
                    font-size: 24px;
                }
                .invoice-details {
                    margin-bottom: 20px;
                }
                .invoice-details p {
                    margin: 0;
                    padding: 5px 0;
                    font-size: 14px;
                }
                .table-container {
                    overflow-x: auto;
                }
                .table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                .table th, .table td {
                    border: 1px solid #ddd;
                    padding: 10px;
                    text-align: left;
                    font-size: 14px;
                }
                .table th {
                    background-color: #f2f2f2;
                }
                .invoice-summary {
                    text-align: right;
                    margin-top: 20px;
                }
                .invoice-summary p {
                    font-size: 14px;
                    margin: 5px 0;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    color: #888;
                    margin-top: 20px;
                }
            </style>
        </head>
        <body>
            <div class="invoice-container">
                <div class="invoice-header">
                    <h1>Sales Invoice</h1>
                    <p>Invoice #: <?= htmlspecialchars($invoices[0]['invoice_id'] ?? 'N/A') ?></p>
                    <p>Date: <?= htmlspecialchars($invoices[0]['date'] ?? date('Y-m-d H:i:s')) ?></p>
                </div>

                <div class="invoice-details">
                    <p><strong>Bill To:</strong> <?= htmlspecialchars($invoices[0]['customer_name'] ?? 'N/A') ?></p>
                    <p><strong>Customer ID:</strong> <?= htmlspecialchars($invoices[0]['customer_id'] ?? 'N/A') ?></p>
                </div>

                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Products</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($invoices as $invoice): ?>
                                <tr>
                                    <td><?= htmlspecialchars($invoice['products']) ?></td>
                                    <td><?= htmlspecialchars($invoice['qty']) ?></td>
                                    <td>₱<?= number_format($invoice['unit_price'], 2) ?></td>
                                    <td>₱<?= number_format($invoice['total_amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="invoice-summary">
                    <p><strong>Subtotal:</strong> ₱<?= number_format(array_sum(array_column($invoices, 'total_amount')), 2) ?></p>
                    <p><strong>Sales Tax:</strong> ₱<?= number_format(array_sum(array_column($invoices, 'sales_tax')), 2) ?></p>
                    <p><strong>Total:</strong> ₱<?= number_format(array_sum(array_column($invoices, 'total_amount')) + array_sum(array_column($invoices, 'sales_tax')), 2) ?></p>
                    <p><strong>Payment Status:</strong> Paid</p>
                </div>

                <div class="footer">
                    <p>Thank you for your business!</p>
                </div>
            </div>
        </body>
        </html>
        <?php
        // Get the HTML content
        $html_content = ob_get_clean();

        // Set the headers for the file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="sales_invoice_' . $customer_id . '.html"');
        header('Content-Length: ' . strlen($html_content));

        // Output the content as the file
        echo $html_content;
        exit;

    } else {
        echo "No invoices found for this customer.";
    }
} else {
    echo "Invalid customer ID.";
}
?>
