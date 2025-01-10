<?php
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

$sql = "UPDATE expenses SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$status, $id]);

echo json_encode(['message' => 'Expense status updated successfully!']);
?>
