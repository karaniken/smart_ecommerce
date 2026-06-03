<?php
require_once 'includes/header.php';
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit();
}
?>
<h2>Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['user_email']) ?>!</p>
<p>User ID: <?= $_SESSION['user_id'] ?></p>
<a href="logout.php">Logout</a>
<?php require_once 'includes/footer.php'; ?>
