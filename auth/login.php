<?php
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

include '../includes/db.php';
$key = "ordermanagementsecretkey";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $hashed_password);
    if ($stmt->fetch() && password_verify($password, $hashed_password))
    {
        $payload = array("iss" => "localhost",
                         "aud" => "localhost",
                         "iat" => time(),
                         "nbf" => time(),
                         "exp" => time() + 3600,
                         "data" => array("id" => $id, "email" => $email)
                       );
        $jwt = JWT::encode($payload, $key, 'HS256');
        setcookie("token", $jwt, time()+3600, "/", "", false, true);
        header("Location: ../index.php");
        exit();
    } 
    else
        $error = "Invalid credentials.";
}
?>

<?php include '../includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0 mt-5">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h2>
            </div>
            <div class="card-body bg-light">
                <?php if(isset($error)){ ?>
                    <div class="alert alert-danger text-center"><?php $error ?></div>
                <?php } ?>
                <form method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
