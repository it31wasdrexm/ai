<?php
session_start();
require 'config.php';
require 'openai_helper.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}


$lang = $_SESSION['lang'] ?? 'ru';


$translations = [
    'ru' => [
        'title' => 'Анализ резюме',
        'placeholder' => 'Вставьте резюме...',
        'analyze' => 'Анализировать',
        'result' => 'Результат:',
        'account' => 'Личный кабинет',
        'messages' => 'Сообщения',
        'ai_name' => 'AI Analyzer',
        'prompt' => "Проанализируй резюме и дай рекомендации для идеального резюме. Укажи шанс поступления в вузы и колледжи США и Казахстана.\nРезюме:\n",
    ],
    'en' => [
        'title' => 'Resume Analysis',
        'placeholder' => 'Paste your resume...',
        'analyze' => 'Analyze',
        'result' => 'Result:',
        'account' => 'Account',
        'messages' => 'Messages',
        'ai_name' => 'AI Analyzer',
        'prompt' => "Analyze the resume and provide recommendations for an ideal one. Indicate admission chances to US and Kazakhstan universities and colleges.\nResume:\n",
    ],
    'kk' => [
        'title' => 'Түйіндеме талдауы',
        'placeholder' => 'Түйіндемеңізді қойыңыз...',
        'analyze' => 'Талдау',
        'result' => 'Нәтиже:',
        'account' => 'Жеке кабинет',
        'messages' => 'Хабарламалар',
        'ai_name' => 'AI Analyzer',
        'prompt' => "Түйіндемені талда және мінсіз нұсқасын ұсын. АҚШ және Қазақстан университеттеріне түсу мүмкіндігін көрсет.\nТүйіндеме:\n",
    ]
];

$feedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resume = $_POST['resume'];
    $file_name = null;

    if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] === 0) {
        if (!is_dir('uploads')) mkdir('uploads', 0777, true);
        $file_name = time() . '_' . basename($_FILES['resume_file']['name']);
        move_uploaded_file($_FILES['resume_file']['tmp_name'], 'uploads/' . $file_name);

        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_text = '';

        if ($ext === 'txt') {
            $file_text = file_get_contents('uploads/' . $file_name);
        } elseif ($ext === 'pdf') {
            $file_text = '[PDF файл, содержимое не извлечено]';
        } elseif (in_array($ext, ['doc', 'docx'])) {
            $file_text = '[DOC/DOCX файл, содержимое не извлечено]';
        }

        $resume .= "\n\n" . $file_text;
    }


    $feedback = analyzeWithOpenAI($translations[$lang]['prompt'] . $resume);

    
    $stmt = $pdo->prepare('INSERT INTO analyses (user_id,type,input_text,file_name,result) VALUES (?,?,?,?,?)');
    $stmt->execute([$_SESSION['user_id'], 'resume', $resume, $file_name, $feedback]);
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
    <form method="POST" enctype="multipart/form-data" style="width: 100%; max-width: 600px;">
        <h1 style="margin-bottom: 20px;"><?= htmlspecialchars($translations[$lang]['title']) ?></h1>
        <textarea name="resume" placeholder="<?= htmlspecialchars($translations[$lang]['placeholder']) ?>" required></textarea>
        <input type="file" name="resume_file" accept=".txt,.pdf,.doc,.docx">
        <button type="submit"><?= htmlspecialchars($translations[$lang]['analyze']) ?></button>
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
