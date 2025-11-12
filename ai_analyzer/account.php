<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $lang = $_POST['lang'];

    
    $stmt = $pdo->prepare('UPDATE users SET name=?, lang=? WHERE id=?');
    $stmt->execute([$name, $lang, $user_id]);
    $_SESSION['lang'] = $lang;
    $currentLang = $lang;
    $T = $langs[$currentLang];

    $msg = ($currentLang == 'kk') ? 'Мәліметтер жаңартылды' : (($currentLang == 'en') ? 'Data updated' : 'Данные обновлены');
}


$stmt = $pdo->prepare('SELECT * FROM users WHERE id=?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($currentLang) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= ($currentLang == 'kk') ? 'Жеке кабинет' : (($currentLang == 'en') ? 'Profile' : 'Личный кабинет') ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
<style>
select { padding: 8px; border-radius: 6px; border: 1px solid #ccc; }
</style>
</head>
<body>

<header class="header">
    <div class="header-left">
        <a href="account.php" title="<?= $T['profile'] ?? 'Профиль' ?>"><i class="bi bi-person-circle"></i></a>
    </div>
    <a href="index.php"><div class="header-center">AI Analyzer</div></a>
    <div class="header-right">
        <a href="logout.php"><?= ($currentLang == 'kk') ? 'Шығу' : (($currentLang == 'en') ? 'Logout' : 'Выйти') ?></a>
    </div>
</header>

<main class="main-container" style="flex-direction: column; align-items: center; padding-top: 140px; gap: 30px;">

    <form method="POST" style="background:#fff; padding: 40px 30px; border-radius:20px; box-shadow:0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; display:flex; flex-direction:column; gap:20px; text-align:center;">
        <h1 style="margin-bottom: 20px;">
            <?= ($currentLang == 'kk') ? 'Жеке кабинет' : (($currentLang == 'en') ? 'Profile' : 'Личный кабинет') ?>
        </h1>
        
        <input name="name" value="<?= htmlspecialchars($user['name']) ?>" placeholder="<?= ($currentLang == 'kk') ? 'Аты' : (($currentLang == 'en') ? 'Name' : 'Имя') ?>" required>

       
        <label for="lang"><?= ($currentLang == 'kk') ? 'Тілді таңдау' : (($currentLang == 'en') ? 'Choose language' : 'Выберите язык') ?>:</label>
        <select name="lang" id="lang">
            <option value="ru" <?= $currentLang == 'ru' ? 'selected' : '' ?>>Русский</option>
            <option value="kk" <?= $currentLang == 'kk' ? 'selected' : '' ?>>Қазақша</option>
            <option value="en" <?= $currentLang == 'en' ? 'selected' : '' ?>>English</option>
        </select>
        
        <button type="submit"><?= ($currentLang == 'kk') ? 'Сақтау' : (($currentLang == 'en') ? 'Save' : 'Сохранить') ?></button>
        
        <?php if (!empty($msg)) echo "<p style='color:green; margin-top:10px;'>$msg</p>"; ?>
        
        <a href="index.php" style="margin-top: 10px; color:#000;">
            <?= ($currentLang == 'kk') ? 'Артқа' : (($currentLang == 'en') ? 'Back' : 'Назад') ?>
        </a>
    </form>

</main>

</body>
</html>
