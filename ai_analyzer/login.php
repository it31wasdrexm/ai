<?php 
require 'config.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && hash('sha256', $password) === $user['password']){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['lang'] = $user['lang'] ?? 'en'; 
        header('Location: index.php'); 
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="styles.css">
<title>Login</title>
</head>
<body>
<div class="form-wrapper">
    <form method="POST">
        <h1>Login</h1>
        <input name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <?php if(!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
        <a href="register.php">Register</a>
    </form>
</div>
</body>
</html>
