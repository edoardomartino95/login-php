<?php
include 'config.php';

$message = "";

if(isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //crittazione della password
    $nickname = trim($_POST['nickname']);

   // var_dump($nickname);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_due = $conn->prepare("SELECT id FROM users WHERE nickname = ?");
    $check->execute([$email]);
    $check_due->execute([$nickname]);

    if($check->rowCount() > 0) {
        $message = "Email already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users(fullname, email, password, nickname) VALUES(?,?,?,?)");
        if($stmt->execute([$fullname, $email, $password, $nickname])) {
            $message = "Registration successful!";
        } else {
            $message = "Something went wrong!";
        }
    }

    if($check_due->rowCount() > 0) {
        $message = "Nickname already exists!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users(fullname, email, password, nickname) VALUES(?,?,?,?)");
        if($stmt->execute([$fullname, $email, $password, $nickname])) {
            $message = "Registration successful!";
        } else {
            $message = "Something went wrong!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <form method="POST" class="form-box">
        <h2>Create Account</h2>
        <p class="message"><?= $message ?></p>
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="nickname" name="nickname"placeholder="Nickname">
        <button type="submit" name="register">Register</button>
        <p>Already have account? <a href="login.php">Login</a></p>
    </form>
</div>
<script src="script.js"></script>
</body>
</html>