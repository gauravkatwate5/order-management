<?php
setcookie("token", "", time() - 3600, "/", "", false, true);
header("Location: login.php");
exit();