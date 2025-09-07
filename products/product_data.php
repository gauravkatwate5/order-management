<?php
// product_data.php for product-related data operations
// Example: Fetch product data from the database and return as JSON
include '../includes/db.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
echo json_encode($products);
