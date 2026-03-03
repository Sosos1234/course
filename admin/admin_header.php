<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle ?? 'Админ') ?> — ТРЦ Европа 27</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="admin/admin.css">
</head>
<body class="admin-body">
<header class="admin-header">
    <div class="admin-header-inner">
        <a href="index.php?page=admin" class="admin-logo">
            <span class="admin-logo-icon">⚙️</span>
            <span>Админ-панель ТРЦ Европа 27</span>
        </a>
        <nav class="admin-nav">
            <a href="index.php?page=admin-shops">Магазины</a>
            <a href="index.php?page=admin-products">Товары</a>
            <a href="index.php?page=admin-news">Новости</a>
            <a href="index.php?page=admin">Дашборд</a>
            <a href="index.php?page=admin&logout=1" class="admin-logout">Выход</a>
        </nav>
    </div>
</header>
<main class="admin-main">
