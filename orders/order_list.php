

<?php
require '../includes/auth.php';
$user_id = check_jwt();
include '../includes/db.php';
include '../includes/header.php';

$result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");

// Get all cities for dropdown
$cities = [];
$city_result = $conn->query("SELECT DISTINCT city FROM users ORDER BY city ASC");
while($row = $city_result->fetch_assoc()) {
        $cities[] = $row['city'];
}
?>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-lg border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                <h2 class="mb-0"><i class="bi bi-list-check"></i> Your Orders</h2>
                <div class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
                    <form class="d-flex align-items-center gap-2" method="get" action="../reports/export.php" style="margin-bottom:0;">
                        <input type="hidden" name="filter" value="city">
                        <select name="city" class="form-select form-select-sm" required>
                            <option value="">Select City</option>
                            <?php foreach($cities as $city) { ?>
                                <option value="<?php echo htmlspecialchars($city) ?>"><?php echo htmlspecialchars($city) ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-info btn-sm"><i class="bi bi-download"></i> Export City Orders</button>
                    </form>
                    <a href="../reports/export.php" class="btn btn-success"><i class="bi bi-download"></i> Export </a>
                </div>
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
                        <?php while($order = $result->fetch_assoc()) {?>
                            <tr>
                                <td class="fw-semibold text-primary">#<?php echo $order['id'] ?></td>
                                <td><?php echo date('d-m-Y H:i', strtotime($order['created_at'])) ?></td>
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
