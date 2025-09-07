<?php
session_start();
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';

$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);
if($quantity < 1) $quantity = 1;

if(!isset($_SESSION['cart']))
    $_SESSION['cart'] = [];

if(isset($_SESSION['cart'][$product_id]))
    $_SESSION['cart'][$product_id] += $quantity;
else
    $_SESSION['cart'][$product_id] = $quantity;


header("Location: view_cart.php");
exit();
