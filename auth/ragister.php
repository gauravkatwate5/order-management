
<?php
include '../includes/db.php';
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $city = $_POST['city'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, city, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $city, $password);
    if ($stmt->execute())
    {
        header("Location: login.php");
        exit();
    } 
    else
        $error = "Error: " . $stmt->error;
}
?>

<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-success text-white text-center">
                <h2 class="mb-0"><i class="bi bi-person-plus"></i> Register</h2>
            </div>
            <div class="card-body bg-light">
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger text-center"><?php $error ?></div>
                <?php } ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" id="city" name="city" class="form-control" placeholder="City" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100"><i class="bi bi-person-plus"></i> Register</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
