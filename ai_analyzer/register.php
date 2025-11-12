<?php 
require 'config.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);
    $lang = $_POST['lang'] ?? 'en'; 

    $stmt = $pdo->prepare('INSERT INTO users (name,email,password,lang) VALUES (?,?,?,?)');
    $stmt->execute([$name, $email, $password, $lang]);

    header('Location: login.php'); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">
<title>Register</title>
</head>
<body>
<div class="form-wrapper">
    <form method="POST">
        <h1>Register</h1>
        <input name="name" placeholder="Name" required>
        <input name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Create Account</button>
        <a href="login.php">Login</a>
    </form>
</div>
</body>
</html>
