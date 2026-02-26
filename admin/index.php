<?php
/**
 * Административная панель
 */
session_start();
$app = require __DIR__ . '/../bootstrap.php';
$config = $app['config'];

// Проверка авторизации
$isAuth = ($_SESSION['admin'] ?? false) === true;
if (!$isAuth && ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auth_login']))) {
    if ($_POST['auth_login'] === ($config['admin']['login'] ?? '') && $_POST['password'] === ($config['admin']['password'] ?? '')) {
        $_SESSION['admin'] = true;
        $isAuth = true;
    }
}
if (isset($_GET['logout'])) {
    unset($_SESSION['admin']);
    header('Location: index.php?page=admin');
    exit;
}
if (!$isAuth) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Вход в админ-панель</title>
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>
    <body>
    <div class="container" style="max-width:400px;margin:4rem auto;">
        <h1>Вход</h1>
        <form method="post">
            <p><label>Логин: <input type="text" name="auth_login" value="<?= htmlspecialchars($_POST['auth_login'] ?? '') ?>" required></label></p>
            <p><label>Пароль: <input type="password" name="password" required></label></p>
            <p><button type="submit" class="btn btn-primary">Войти</button></p>
        </form>
        <p><a href="../index.php">На главную</a></p>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// Главная страница админки
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header class="header">
    <div class="container">
        <a href="?page=admin" class="logo">Админ-панель ТРЦ Европа 27</a>
        <a href="?page=admin&logout=1" style="color:#fff;margin-left:auto;">Выход</a>
    </div>
</header>
<main class="main">
    <div class="container" style="padding:2rem 0;">
        <h1>Управление</h1>
        <ul>
            <li><a href="../index.php?page=import">Импорт данных из JSON</a></li>
            <li><a href="../index.php?page=shops">Просмотр магазинов</a></li>
        </ul>
        <p><a href="../index.php">На главную</a></p>
    </div>
</main>
</body>
</html>
