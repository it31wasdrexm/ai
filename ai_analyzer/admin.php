<?php
require 'config.php';
if(!isset($_SESSION['user_id']) || $_SESSION['role']!=='admin') die('Нет доступа');

$lang = $_SESSION['lang'] ?? 'ru'; 


$translations = [
    'ru' => [
        'admin_panel' => 'Панель администратора',
        'home' => 'Главная',
        'account' => 'Личный кабинет',
        'logout' => 'Выйти',
        'user' => 'Пользователь',
        'email' => 'Email',
        'actions' => 'Действия',
        'reply' => 'Ответить',
        'view_requests' => 'Просмотр запросов'
    ],
    'en' => [
        'admin_panel' => 'Admin Panel',
        'home' => 'Home',
        'account' => 'Account',
        'logout' => 'Logout',
        'user' => 'User',
        'email' => 'Email',
        'actions' => 'Actions',
        'reply' => 'Reply',
        'view_requests' => 'View Requests'
    ],
    'kk' => [
        'admin_panel' => 'Әкімші панелі',
        'home' => 'Басты бет',
        'account' => 'Жеке кабинет',
        'logout' => 'Шығу',
        'user' => 'Пайдаланушы',
        'email' => 'Email',
        'actions' => 'Әрекеттер',
        'reply' => 'Жауап беру',
        'view_requests' => 'Сұраныстарды қарау'
    ]
];

$users = $pdo->query('SELECT * FROM users WHERE role="user" ORDER BY name')->fetchAll();
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($translations[$lang]['admin_panel']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="styles.css">
<style>
.user-table { width: 90%; max-width: 1000px; margin: 140px auto 50px; border-collapse: separate; border-spacing: 0 10px; font-size: 16px; } .user-table th, .user-table td { padding: 15px 20px; text-align: left; } .user-table th { background: #000; color: #fff; border-radius: 15px 15px 0 0; } .user-table tr { background: #fff; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.2s, box-shadow 0.2s; } .user-table tr:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); } .user-table td { vertical-align: middle; } .action-btn { padding: 8px 12px; margin-right: 5px; border: none; border-radius: 15px; background: #000; color: #fff; cursor: pointer; font-weight: bold; transition: all 0.3s; } .action-btn:hover { background: #333; transform: scale(1.05); } .header { display: flex; justify-content: space-between; align-items: center; background: #fff; padding: 15px 30px; width: 100%; position: fixed; top: 0; left: 0; z-index: 100; box-shadow: 0 4px 20px rgba(0,0,0,0.1); } .header-left a, .header-right a { color: #000; text-decoration: none; font-size: 24px; margin-right: 20px; transition: transform 0.2s, color 0.3s; } .header-left a:hover, .header-right a:hover { color: #555; transform: scale(1.1); } .header-center { font-size: 28px; font-weight: bold; }
</style>
</head>
<body>

<header class="header">
    <div class="header-left">
        <a href="index.php" title="<?= htmlspecialchars($translations[$lang]['home']) ?>"><i class="bi bi-house-fill"></i></a>
        <a href="account.php" title="<?= htmlspecialchars($translations[$lang]['account']) ?>"><i class="bi bi-person-circle"></i></a>
    </div>
    <div class="header-center"><?= htmlspecialchars($translations[$lang]['admin_panel']) ?></div>
    <div class="header-right">
        <a href="logout.php"><?= htmlspecialchars($translations[$lang]['logout']) ?></a>
    </div>
</header>

<table class="user-table">
<tr>
    <th><?= htmlspecialchars($translations[$lang]['user']) ?></th>
    <th><?= htmlspecialchars($translations[$lang]['email']) ?></th>
    <th><?= htmlspecialchars($translations[$lang]['actions']) ?></th>
</tr>

<?php foreach($users as $u): ?>
<tr>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td>
        <a href="chat.php?user_id=<?= $u['id'] ?>"><button class="action-btn"><?= htmlspecialchars($translations[$lang]['reply']) ?></button></a>
        <a href="user_requests.php?user_id=<?= $u['id'] ?>"><button class="action-btn"><?= htmlspecialchars($translations[$lang]['view_requests']) ?></button></a>
    </td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
