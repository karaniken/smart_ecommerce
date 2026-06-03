<?php
require_once 'includes/header.php';
requireLogin();

$id = $_GET['id'] ?? 0;
$message = '';
$error = '';

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if (empty($name) || $price <= 0) {
        $error = 'Name and valid price are required.';
    } else {
        $updateStmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
        if ($updateStmt->execute([$name, $description, $price, $stock, $id])) {
            $message = 'Product updated successfully!';
            // Refresh product data
            $stmt->execute([$id]);
            $product = $stmt->fetch();
        } else {
            $error = 'Update failed.';
        }
    }
}
?>

<h2>Edit Product</h2>
<?php if ($message) echo "<p class='success'>$message</p>"; ?>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
    <label>Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Description:</label>
    <textarea name="description" rows="3"><?= htmlspecialchars($product['description']) ?></textarea>

    <label>Price ($):</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Stock:</label>
    <input type="number" name="stock" value="<?= $product['stock'] ?>">

    <button type="submit" class="btn btn-success">Update</button>
    <a href="products.php" class="btn">Cancel</a>
</form>

<?php require_once 'includes/footer.php'; ?>
