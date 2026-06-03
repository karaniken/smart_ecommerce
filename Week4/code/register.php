<?php
require_once 'includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($email) || empty($password)) {
        $error = 'All fields required';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->execute([$email, $hashed]);
            $success = 'Registration successful. <a href="login.php">Login here</a>';
        } catch (PDOException $e) {
            $error = 'Email already exists';
        }
    }
}
?>

<h2>Register</h2>
<?php if ($error) echo "<p class='error'>$error</p>"; ?>
<?php if ($success) echo "<p class='success'>$success</p>"; ?>
<form method="POST">
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>
    <label>Password (min 6):</label><br>
    <input type="password" name="password" required><br><br>
    <label>Confirm Password:</label><br>
    <input type="password" name="confirm_password" required><br><br>
    <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login</a></p>

<?php require_once 'includes/footer.php'; ?>
