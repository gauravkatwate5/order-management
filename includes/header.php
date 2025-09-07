<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Order Management System</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../index.php"><i class="bi bi-box-seam"></i> Order Management</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php if(isset($_COOKIE['token'])){ ?>
          <li class="nav-item"><a class="nav-link" href="../cart/view_cart.php"><i class="bi bi-cart"></i> Cart</a></li>
          <li class="nav-item"><a class="nav-link" href="../orders/order_list.php"><i class="bi bi-list-check"></i> Orders</a></li>
          <li class="nav-item"><a class="nav-link" href="../auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
        <?php } else { ?>
          <li class="nav-item"><a class="nav-link" href="../auth/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
          <li class="nav-item"><a class="nav-link" href="../auth/register.php"><i class="bi bi-person-plus"></i> Register</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
