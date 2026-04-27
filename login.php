<?php
include 'config.php';

$message = "";

if(isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $nickname = trim($_POST['nickname']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['nickname'] = $user['nickname'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="POST" class="form-box">
        <h2>Login</h2>
        <p class="message"><?= $message ?></p>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="text" name="nickname" placeholder="Nickname" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
        <p>No account? <a href="register.php">Register</a></p>
    </form>
</div>
<script src="script.js"></script>
</body>
</html>