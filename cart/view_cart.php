
<?php
session_start();
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-cart"></i> Your Cart</h2>
            </div>
            <div class="card-body bg-light">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                        $total = 0;
                        foreach($cart as $product_id => $qty)
                        {
                                $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $product = $result->fetch_assoc();
                                $subtotal = $product['price'] * $qty;
                                $total += $subtotal;
                        ?>
                            <tr>
                                <td class="fw-semibold text-primary"><?= htmlspecialchars($product['name']) ?></td>
                                <td><span class="badge bg-success">₹<?= $product['price'] ?></span></td>
                                <td><?= $qty ?></td>
                                <td><span class="badge bg-info text-dark">₹<?= $subtotal ?></span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4 class="fw-bold">Total: <span class="text-success">₹<?= $total ?></span></h4>
                    <a href="../orders/place_order.php" class="btn btn-success btn-lg"><i class="bi bi-bag-check"></i> Place Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
