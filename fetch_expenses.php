
<?php
include 'db.php';

$sql = "SELECT * FROM expenses";
$stmt = $conn->query($sql);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($expenses);
?>