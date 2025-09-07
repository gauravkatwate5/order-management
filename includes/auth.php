<?php
require_once __DIR__ . '/../vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

$key = "ordermanagementsecretkey";

function check_jwt()
{
    global $key;
    if(!isset($_COOKIE['token']))
    {
        header("Location: ../auth/login.php");
        exit();
    }
    $token = $_COOKIE['token'];
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        return $decoded->data->id;
    } catch (Exception $e) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>
