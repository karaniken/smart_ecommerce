<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    echo "<h2>Welcome!</h2>";
    echo "<p>Email: $email</p>";
    echo "<p>Password length: " . strlen($password) . " characters</p>";
    echo "<p><a href='login.html'>Back to login</a></p>";
} else {
    echo "Invalid request.";
}
?>
