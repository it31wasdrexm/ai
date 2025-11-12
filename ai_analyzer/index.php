<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AI Analyzer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
<style>
    .lang-switch {
        position: absolute;
        right: 20px;
        top: 15px;
    }
    .lang-switch a {
        margin: 0 3px;
        text-decoration: none;
        font-size: 18px;
    }
</style>
</head>
<body>
<header class="header">
    <div class="header-left">
        <a href="account.php" title="<?= $T['profile'] ?>"><i class="bi bi-person-circle"></i></a>
    </div>
    <a href="index.php"><div class="header-center">AI Analyzer</div></a>

    
</header>

<main class="main-container">
    <a href="resume_analyzer.php" class="big-card">
        <i class="bi bi-file-earmark-text"></i>
        <span><?= ($currentLang == 'kk') ? 'Резюме талдау' : (($currentLang == 'en') ? 'Resume Analysis' : 'Анализ резюме') ?></span>
    </a>
    <a href="gpa_calculator.php" class="big-card">
        <i class="bi bi-mortarboard"></i>
        <span><?= ($currentLang == 'kk') ? 'GPA калькуляторы' : (($currentLang == 'en') ? 'GPA Calculator' : 'GPA калькулятор') ?></span>
    </a>
    <?php if($isAdmin): ?>
    <a href="admin.php" class="big-card">
        <i class="bi bi-people-fill"></i>
        <span><?= ($currentLang == 'kk') ? 'Пайдаланушылар мен сұраулар' : (($currentLang == 'en') ? 'Users & Requests' : 'Пользователи и запросы') ?></span>
    </a>
    <?php endif; ?>
</main>
</body>
</html>
