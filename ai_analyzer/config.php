<?php
session_start();
require_once 'lang.php';


if (isset($_GET['lang']) && in_array($_GET['lang'], ['ru', 'kk', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}


if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
}

$currentLang = $_SESSION['lang'];


if (isset($langs[$currentLang])) {
    $T = $langs[$currentLang];
} else {
    $T = $langs['en']; 
}


try {
    $pdo = new PDO("mysql:host=localhost;dbname=ai_analyzer;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}
?>
