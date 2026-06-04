<?php
require_once 'includes/header.php';
requireLogin();
?>
<h2>Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['user_email']) ?>!</p>
<p><a href="products.php">Manage Products</a></p>
<a href="logout.php">Logout</a>
<?php require_once 'includes/footer.php'; ?>
