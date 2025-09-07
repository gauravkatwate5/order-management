<?php
// export.php - Export orders as CSV
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="orders_export.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Order ID', 'Date', 'Product', 'Quantity', 'Price']);

$sql = "SELECT o.id, o.created_at, p.name, oi.quantity, p.price FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE o.user_id = ? ORDER BY o.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($order_id, $date, $product, $qty, $price);
while($stmt->fetch()) {
    fputcsv($output, [$order_id, $date, $product, $qty, $price]);
}
fclose($output);
exit();
