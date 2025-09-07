
<?php
// export.php - Export orders as CSV (city-wise or user-wise)
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';

// Get filter from query string
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'user';
$city = isset($_GET['city']) ? trim($_GET['city']) : '';

header('Content-Type: text/csv');
if ($filter === 'city' && $city !== '') {
    $filename = 'orders_city_' . preg_replace('/[^a-zA-Z0-9]/', '_', $city) . '.csv';
} else {
    $filename = 'orders_user_' . $user_id . '.csv';
}
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Order ID', 'Date', 'User', 'City', 'Product', 'Quantity', 'Price']);

if ($filter === 'city' && $city !== '') {
    // City-wise export
    $sql = "SELECT o.id, o.created_at, u.name, u.city, p.name, oi.quantity, p.price
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE u.city = ?
            ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $city);
} else {
    // User-wise export (current user)
    $sql = "SELECT o.id, o.created_at, u.name, u.city, p.name, oi.quantity, p.price
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ?
            ORDER BY o.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
}

$stmt->execute();
$stmt->bind_result($order_id, $date, $user, $city_val, $product, $qty, $price);
while($stmt->fetch()) {
    fputcsv($output, [$order_id, $date, $user, $city_val, $product, $qty, $price]);
}
fclose($output);
exit();
