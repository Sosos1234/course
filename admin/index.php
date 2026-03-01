<?php
/**
 * Административная панель
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Вход в админ-панель</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="admin/admin.css">
    </head>
    <body class="admin-body">
    <div class="admin-login-wrap">
        <div class="admin-login-card">
            <div class="admin-login-header">
                <span class="admin-login-icon">🔐</span>
                <h1>Вход в админ-панель</h1>
                <p>ТРЦ «Европа 27»</p>
            </div>
            <form method="post" class="admin-login-form">
                <div class="form-group">
                    <label for="auth_login">Логин</label>
                    <input type="text" id="auth_login" name="auth_login" value="<?= htmlspecialchars($_POST['auth_login'] ?? '') ?>" required autocomplete="username">
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </form>
            <a href="index.php" class="admin-login-back">← На главную</a>
        </div>
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админ-панель — ТРЦ Европа 27</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="admin-header-inner">
        <a href="index.php?page=admin" class="admin-logo">
            <span class="admin-logo-icon">⚙️</span>
            <span>Админ-панель ТРЦ Европа 27</span>
        </a>
        <a href="index.php?page=admin&logout=1" class="admin-logout">Выход</a>
    </div>
</header>
<main class="admin-main">
    <div class="admin-container">
        <div class="admin-welcome">
            <h1>Режим администратора</h1>
            <p>Управление контентом информационного ресурса</p>
        </div>
        <div class="admin-dashboard">
            <a href="index.php?page=import" class="admin-card admin-card-accent">
                <span class="admin-card-icon">📥</span>
                <h3>Импорт данных</h3>
                <p>Загрузка магазинов и товаров из JSON</p>
            </a>
            <a href="index.php?page=shops" class="admin-card">
                <span class="admin-card-icon">🏪</span>
                <h3>Магазины</h3>
                <p>Каталог арендаторов</p>
            </a>
            <a href="index.php?page=products" class="admin-card">
                <span class="admin-card-icon">📦</span>
                <h3>Товары и услуги</h3>
                <p>Полный список с фильтрами</p>
            </a>
            <a href="index.php?page=news" class="admin-card">
                <span class="admin-card-icon">📰</span>
                <h3>Новости</h3>
                <p>Акции и анонсы</p>
            </a>
        </div>
        <div class="admin-footer-actions">
            <a href="index.php" class="admin-view-site">
                <span>👁</span> На сайт (режим посетителя)
            </a>
        </div>
    </div>
</main>
</body>
</html>
