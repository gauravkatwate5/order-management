
<?php
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$result = $conn->query("SELECT * FROM products");
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-box2-heart-fill"></i> Product List</h2>
            </div>
            <div class="card-body bg-light">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($row = $result->fetch_assoc()){ ?>
                            <tr>
                                <td class="fw-semibold text-primary"><?php echo htmlspecialchars($row['name']) ?></td>
                                <td><span class="badge bg-success">₹<?php echo $row['price'] ?></span></td>
                                <td><?php echo $row['stock'] ?></td>
                                <td>
                                    <form method="post" action="../cart/add_to_cart.php" class="d-flex align-items-center gap-2">
                                        <input type="hidden" name="product_id" value="<?php echo $row['id'] ?>">
                                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['stock'] ?>" class="form-control form-control-sm w-50">
                                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-cart-plus"></i> Add</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
