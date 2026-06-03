<?php
require_once 'includes/header.php';
requireLogin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];

    if (empty($name) || $price <= 0) {
        $error = 'Name and valid price are required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $description, $price, $stock])) {
            $message = 'Product added successfully!';
        } else {
            $error = 'Failed to add product.';
        }
    }
}
?>

<h2>Add Product</h2>
<?php if ($message) echo "<p class='success'>$message</p>"; ?>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
    <label>Name:</label>
    <input type="text" name="name" required>

    <label>Description:</label>
    <textarea name="description" rows="3"></textarea>

    <label>Price ($):</label>
    <input type="number" step="0.01" name="price" required>

    <label>Stock:</label>
    <input type="number" name="stock" value="0">

    <button type="submit" class="btn btn-success">Save</button>
    <a href="products.php" class="btn">Cancel</a>
</form>

<?php require_once 'includes/footer.php'; ?>
