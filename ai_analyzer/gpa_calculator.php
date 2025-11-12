<?php
session_start();
require 'config.php';
require 'openai_helper.php';

if (!isset($_SESSION['user_id'])) header('Location: login.php');


$lang = $_SESSION['lang'] ?? 'ru';


$translations = [
    'ru' => [
        'title' => 'GPA Калькулятор',
        'placeholder' => 'Введите GPA',
        'calculate' => 'Рассчитать',
        'result' => 'Результат:',
        'account' => 'Личный кабинет',
        'messages' => 'Сообщения',
        'ai_name' => 'AI Analyzer',
        'prompt' => "GPA: %s. Подскажи университеты и колледжи США и Казахстана, куда есть шанс поступить. Дай рекомендации по улучшению шансов."
    ],
    'en' => [
        'title' => 'GPA Calculator',
        'placeholder' => 'Enter GPA',
        'calculate' => 'Calculate',
        'result' => 'Result:',
        'account' => 'Account',
        'messages' => 'Messages',
        'ai_name' => 'AI Analyzer',
        'prompt' => "GPA: %s. Suggest US and Kazakhstan universities and colleges where there is a chance of admission. Give recommendations to improve chances."
    ],
    'kk' => [
        'title' => 'GPA Есептегіш',
        'placeholder' => 'GPA енгізіңіз',
        'calculate' => 'Есептеу',
        'result' => 'Нәтиже:',
        'account' => 'Жеке кабинет',
        'messages' => 'Хабарламалар',
        'ai_name' => 'AI Analyzer',
        'prompt' => "GPA: %s. АҚШ және Қазақстан университеттері мен колледждерін ұсын, қайда түсу мүмкіндігі бар. Мүмкіндіктерді жақсарту бойынша кеңестер бер."
    ]
];

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gpa = $_POST['gpa'];
    $feedback = analyzeWithOpenAI(sprintf($translations[$lang]['prompt'], $gpa));

    $stmt = $pdo->prepare('INSERT INTO analyses (user_id,type,input_text,file_name,result) VALUES (?,?,?,?,?)');
    $stmt->execute([$_SESSION['user_id'], 'gpa', $gpa, null, $feedback]);
}

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($translations[$lang]['title']) ?> - <?= htmlspecialchars($translations[$lang]['ai_name']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
</head>
<body>

<header class="header">
    <div class="header-left">
        <a href="account.php" title="<?= htmlspecialchars($translations[$lang]['account']) ?>"><i class="bi bi-person-circle"></i></a>
        
    </div>
    <a href="index.php"><div class="header-center"><?= htmlspecialchars($translations[$lang]['ai_name']) ?></div></a>
</header>

<main class="main-container" style="flex-direction: column; align-items: center; gap: 30px; padding-top: 140px;">
    <form method="POST" style="width: 100%; max-width: 400px;">
        <h1 style="margin-bottom: 20px;"><?= htmlspecialchars($translations[$lang]['title']) ?></h1>
        <input type="number" step="0.01" min="0" max="4.0" name="gpa" placeholder="<?= htmlspecialchars($translations[$lang]['placeholder']) ?>" required>
        <button type="submit"><?= htmlspecialchars($translations[$lang]['calculate']) ?></button>
    </form>

    <?php if (!empty($feedback)): ?>
    <div class="result" style="max-width: 600px; background: #fff; padding: 20px; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.1);">
        <h3><?= htmlspecialchars($translations[$lang]['result']) ?></h3>
        <p><?= nl2br(htmlspecialchars($feedback)) ?></p>
    </div>
    <?php endif; ?>
</main>

</body>
</html>
