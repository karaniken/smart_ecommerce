<?php
require_once 'includes/header.php';
requireLogin();

$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>

<h2>Product List</h2>
<a href="add_product.php" class="btn btn-success">Add New Product</a>
<br><br>
<table>
    <thead>
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
        <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>$<?= number_format($product['price'], 2) ?></td>
            <td><?= $product['stock'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn">Edit</a>
                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger" onclick="return confirm('Delete this product?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once 'includes/footer.php'; ?>
