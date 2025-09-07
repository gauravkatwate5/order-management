
<?php
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-list-check"></i> Your Orders</h2>
            </div>
            <div class="card-body bg-light">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Items</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while($order = $result->fetch_assoc()){?>
                            <tr>
                                <td class="fw-semibold text-primary">#<?= $order['id'] ?></td>
                                <td><?= $order['created_at'] ?></td>
                                <td>
                                    <?php
                                    $stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ?");
                                    $stmt->bind_param("i", $order['id']);
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    while ($item = $res->fetch_assoc())
                                        echo "<span class='badge bg-info text-dark me-1'>Product ID: " . $item['product_id'] . " x " . $item['quantity'] . "</span> ";
                                    $stmt->close();
                                    ?>
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
