<?php
include('header.php');
include('functions.php');
include('db.php');

// Handle the action to approve, reject or delete
if (isset($_GET['id']) && isset($_GET['action'])) {
    $expenseId = $_GET['id'];
    $action = $_GET['action'];

    // Validate the action
    if ($action == 'approve' || $action == 'reject') {
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';
        
        // Update the expense status
        $sql = "UPDATE expenses SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $expenseId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $success_message = "Expense has been successfully " . strtolower($status) . ".";
        } catch (PDOException $e) {
            $error_message = "Error updating status: " . $e->getMessage();
        }
    }
    // Handle delete action
    elseif ($action == 'delete') {
        $sql = "DELETE FROM expenses WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $expenseId, PDO::PARAM_INT);

        try {
            $stmt->execute();
            $success_message = "Expense has been successfully deleted.";
        } catch (PDOException $e) {
            $error_message = "Error deleting record: " . $e->getMessage();
        }
    }
}

// Retrieve expenses with optional filtering by status and department search
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'All';
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Base query for filtering
$sql = "SELECT * FROM expenses WHERE department LIKE :searchQuery";

// Add status filter if specified
if ($statusFilter != 'All') {
    $sql .= " AND status = :statusFilter";
}

$stmt = $conn->prepare($sql);
$searchParam = '%' . $searchQuery . '%';
$stmt->bindParam(':searchQuery', $searchParam, PDO::PARAM_STR);

if ($statusFilter != 'All') {
    $stmt->bindParam(':statusFilter', $statusFilter, PDO::PARAM_STR);
}

$stmt->execute();
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEJw4BvY8J4L+6n78e5Jvi5hPYs12rZar7f1yFvDwsrhMO6YwFOZaGxpJl8fG" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Expenses List</title>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Expenses List</h1>

        <?php if (!empty($success_message)): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (!empty($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-4">
            <div class="btn-group">
                <a href="expenses.php?status=All&search=<?= htmlspecialchars($searchQuery) ?>" class="btn btn-outline-primary <?= $statusFilter == 'All' ? 'active' : '' ?>">All</a>
                <a href="expenses.php?status=Approved&search=<?= htmlspecialchars($searchQuery) ?>" class="btn btn-outline-success <?= $statusFilter == 'Approved' ? 'active' : '' ?>">Approved</a>
                <a href="expenses.php?status=Rejected&search=<?= htmlspecialchars($searchQuery) ?>" class="btn btn-outline-danger <?= $statusFilter == 'Rejected' ? 'active' : '' ?>">Rejected</a>
            </div>

            <form method="GET" action="expenses.php" class="d-flex">
                <input type="text" id="search-bar" name="search" class="form-control w-25" placeholder="Search by department..." value="<?= htmlspecialchars($searchQuery) ?>">
                <button type="submit" class="btn btn-primary ms-2">Search</button>
            </form>
        </div>

        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Department</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $expense): ?>
                <tr>
                    <td><?= htmlspecialchars($expense['department']) ?></td>
                    <td><?= htmlspecialchars($expense['amount']) ?></td>
                    <td><?= htmlspecialchars($expense['date']) ?></td>
                    <td><?= htmlspecialchars($expense['status']) ?></td>
                    <td>
                        <?php if ($expense['status'] == 'Pending'): ?>
                        <a href="expenses.php?id=<?= $expense['id'] ?>&action=approve" class="btn btn-success">Approve</a>
                        <a href="expenses.php?id=<?= $expense['id'] ?>&action=reject" class="btn btn-danger">Reject</a>
                        <?php endif; ?>
                        <a href="expenses.php?id=<?= $expense['id'] ?>&action=delete" class="btn btn-warning" onclick="return confirm('Are you sure you want to delete this expense?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-3 d-flex justify-content-center">
            <a href="track.php" class="btn btn-lg" style="background-color: #28a745; color: white;">Return to Expense Tracker</a>
        </div>
    </div>
</body>

</html>
