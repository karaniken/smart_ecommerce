<?php
require_once 'includes/header.php';
requireLogin();

$id = $_GET['id'] ?? 0;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: products.php');
exit();
?>
