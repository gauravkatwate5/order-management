<?php
require_once 'includes/auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Management System</title>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
  <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-box-seam"></i> Order Management</a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navMenu">
    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
    <?php if(isset($_COOKIE['token'])) { ?>
      <li class="nav-item"><a class="nav-link" href="cart/view_cart.php"><i class="bi bi-cart"></i> Cart</a></li>
      <li class="nav-item"><a class="nav-link" href="orders/order_list.php"><i class="bi bi-list-check"></i> Orders</a></li>
      <li class="nav-item"><a class="nav-link" href="auth/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
    <?php } else { ?>
      <li class="nav-item"><a class="nav-link" href="auth/login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
      <li class="nav-item"><a class="nav-link" href="auth/register.php"><i class="bi bi-person-plus"></i> Register</a></li>
    <?php } ?>
    </ul>
  </div>
  </div>
</nav>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow-lg border-0">
        <div class="card-body text-center">
          <h1 class="display-5 mb-4 fw-bold text-primary">Welcome to Order Management System</h1>
          <?php if(isset($_COOKIE['token'])) { ?>
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <a href="products/list.php" class="card card-hover text-decoration-none h-100">
                <div class="card-body">
                  <i class="bi bi-box2-heart-fill text-success" style="font-size:2rem;"></i>
                  <h5 class="card-title mt-2">View Products</h5>
                </div>
              </a>
            </div>
            <div class="col-md-6">
              <a href="orders/order_list.php" class="card card-hover text-decoration-none h-100">
                <div class="card-body">
                  <i class="bi bi-list-check text-info" style="font-size:2rem;"></i>
                  <h5 class="card-title mt-2">View Orders</h5>
                </div>
              </a>
            </div>
          </div>
          <?php } else { ?>
          <div class="row g-4 mb-4">
            <div class="col-md-6">
              <a href="auth/login.php" class="card card-hover text-decoration-none h-100">
                <div class="card-body">
                  <i class="bi bi-box-arrow-in-right text-primary" style="font-size:2rem;"></i>
                  <h5 class="card-title mt-2">Login</h5>
                </div>
              </a>
            </div>
            <div class="col-md-6">
              <a href="auth/register.php" class="card card-hover text-decoration-none h-100">
                <div class="card-body">
                  <i class="bi bi-person-plus text-success" style="font-size:2rem;"></i>
                  <h5 class="card-title mt-2">Register</h5>
                </div>
              </a>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
