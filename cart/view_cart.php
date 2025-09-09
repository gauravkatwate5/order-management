
<?php
session_start();
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$quantity = 0;
$discount = 0.0;
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
                                $quantity += $qty;
                                $stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
                                $stmt->bind_param("i", $product_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $product = $result->fetch_assoc();
                                $subtotal = $product['price'] * $qty;
                                $total += $subtotal;
                        ?>
                            <tr>
                                <td class="fw-semibold text-primary"><?php echo htmlspecialchars($product['name']) ?></td>
                                <td><span class="badge bg-success">₹<?php echo $product['price'] ?></span></td>
                                <td><?php echo $qty ?></td>
                                <td><span class="badge bg-info text-dark">₹<?php echo $subtotal ?></span></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <?php 
                    if($quantity >= 5 && $quantity <= 10)
                    {
                        $discount = (($total / 100) * 10);
                        $total = $total - $discount;
                    }
                    else if($quantity >= 10)
                    {
                        $discount = (($total / 100) * 20);
                        $total =  $total - $discount;
                    }
                    ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4 class="fw-bold">Discount: <span class="text-success">₹<?php echo $discount ?></span></h4>
                    <h4 class="fw-bold">Total: <span class="text-success">₹<?php echo $total ?></span></h4>
                    <a href="../orders/place_order.php" id="placeOrder" class="btn btn-success btn-lg"><i class="bi bi-bag-check"></i> Place Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
<script>
    document.getElementByID("placeOrder");
</script>
