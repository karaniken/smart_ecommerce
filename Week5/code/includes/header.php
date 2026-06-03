<?php
session_start();
require_once __DIR__ . '/../config/db.php';

function requireLogin() {
    if (!isset($_SESSION['user_email'])) {
        header('Location: login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; margin: 20px; }
        .container { max-width: 1000px; margin: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #2c3e50; color: white; }
        .btn { display: inline-block; padding: 5px 10px; background: #3498db; color: white; text-decoration: none; border-radius: 4px; }
        .btn-danger { background: #e74c3c; }
        .btn-success { background: #2ecc71; }
        .error { color: red; }
        .success { color: green; }
        form input, form textarea { width: 100%; padding: 8px; margin: 5px 0 15px; }
    </style>
</head>
<body>
<div class="container">
    <nav>
        <a href="products.php">Products</a>
        <a href="add_product.php">Add Product</a>
        <?php if (isset($_SESSION['user_email'])): ?>
            <span>Welcome, <?= htmlspecialchars($_SESSION['user_email']) ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
    <hr>
