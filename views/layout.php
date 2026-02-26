<?php
/**
 * Основной шаблон
 */
$page = $page ?? 'home';
$pageTitle = $pageTitle ?? 'Главная';
$siteConfig = $config ?? [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($siteConfig['name'] ?? 'ТРЦ Европа 27') ?> — <?= htmlspecialchars($pageTitle) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="index.php" class="logo"><?= htmlspecialchars($siteConfig['name'] ?? 'ТРЦ Европа 27') ?></a>
            <form action="index.php" method="get" class="search-form">
                <input type="hidden" name="page" value="search">
                <input type="search" name="q" placeholder="Поиск магазинов и товаров..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="search-input">
                <button type="submit" class="search-btn">Найти</button>
            </form>
            <nav class="nav">
                <a href="index.php" class="<?= $page === 'home' ? 'active' : '' ?>">Главная</a>
                <a href="index.php?page=shops" class="<?= $page === 'shops' ? 'active' : '' ?>">Магазины</a>
            </nav>
        </div>
    </header>

    <main class="main">
        <?php
        $pageFile = __DIR__ . '/' . $page . '.php';
        if (file_exists($pageFile)) {
            include $pageFile;
        } else {
            include __DIR__ . '/404.php';
        }
        ?>
    </main>

    <footer class="footer">
        <div class="container">
            <p><strong><?= htmlspecialchars($siteConfig['name'] ?? 'ТРЦ Европа 27') ?></strong></p>
            <p><?= htmlspecialchars($siteConfig['address'] ?? 'г. Липецк, ул. Стаханова, 36') ?></p>
            <p>Режим работы: <?= htmlspecialchars($siteConfig['work_hours'] ?? '9:00 - 22:00') ?></p>
            <p>Горячая линия: <a href="tel:88007707627"><?= htmlspecialchars($siteConfig['phone'] ?? '8-800-770-76-27') ?></a></p>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
