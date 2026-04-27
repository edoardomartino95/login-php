<?php
include 'config.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="form-box">
        <h2>Welcome</h2>
        <p>Hello, <?= htmlspecialchars($_SESSION['fullname']) ?></p>
        <a class="logout-btn" href="logout.php">Logout</a>
    </div>
</div>
</body>
</html>