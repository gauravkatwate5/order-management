<?php
session_start();
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
if(empty($cart))
{
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-warning text-center'>Your cart is empty.</div></div></div></div>";
    include '../includes/footer.php';
    exit();
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND DATE(created_at) = CURDATE()");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($order_count);
$stmt->fetch();
$stmt->close();

if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0)
{
    $total = 0;
    foreach($_SESSION['cart'] as $product_id => $qty)
    {
        $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $subtotal = $product['price'] * $qty;
        $total += $subtotal;
    }   
}

if((int)$total > 20000)
{
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-danger text-center'>You cant add order that amount is greter 20000.</div></div></div></div>";
    include '../includes/footer.php';
    exit();
}

if($order_count >= 5)
{
    $stmt = $conn->prepare("INSERT INTO failed_orders (user_id, reason) VALUES (?, ?)");
    $reason = "Exceeded daily order limit";
    $stmt->bind_param("is", $user_id, $reason);
    $stmt->execute();
    $stmt->close();
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-danger text-center'>You have exceeded today's order limit.</div></div></div></div>";
    include '../includes/footer.php';
    exit();
}

$insufficient = false;
foreach($cart as $product_id => $qty)
{
    $stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();
    if($stock < $qty)
    {
        $insufficient = true;
        break;
    }
}

if($insufficient)
{
    $stmt = $conn->prepare("INSERT INTO failed_orders (user_id, reason) VALUES (?, ?)");
    $reason = "Insufficient stock";
    $stmt->bind_param("is", $user_id, $reason);
    $stmt->execute();
    $stmt->close();
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-danger text-center'>Some items are out of stock.</div></div></div></div>";
    include '../includes/footer.php';
    exit();
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare("INSERT INTO orders (user_id) VALUES (?)");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $order_id = $conn->insert_id;
    $stmt->close();

    foreach($cart as $product_id => $qty)
    {
        $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $product_id, $qty, $price);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $qty, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();
    unset($_SESSION['cart']);
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-success text-center'>Order placed successfully!</div></div></div></div>";
} catch (Exception $e) {
    $conn->rollback();
    echo "<div class='container py-5'><div class='row justify-content-center'><div class='col-12 col-md-8'><div class='alert alert-danger text-center'>Failed to place order.</div></div></div></div>";
}
include '../includes/footer.php';
