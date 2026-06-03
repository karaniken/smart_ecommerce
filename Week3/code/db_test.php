<?php
$conn = mysqli_connect("localhost", "root", "", "test_db");
if (!$conn) {
    die("<p style='color:red'>Connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<h2 style='color:green'>Connected successfully to MariaDB!</h2>";
mysqli_close($conn);
?>
