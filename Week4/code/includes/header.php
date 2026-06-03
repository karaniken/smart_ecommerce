<?php
session_start();
require_once __DIR__ . '/../config/db.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Week 4 - Auth</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
        .container { max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ccc; }
    </style>
</head>
<body>
<div class="container">
