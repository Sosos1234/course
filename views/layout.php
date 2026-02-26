<?php
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header">
        <div class="header-bg"></div>
        <div class="container header-inner">
            <a href="index.php" class="logo">
                <span class="logo-icon">◆</span>
                <?= htmlspecialchars($siteConfig['name'] ?? 'ТРЦ Европа 27') ?>
            </a>
            <form action="index.php" method="get" class="search-form">
                <input type="hidden" name="page" value="search">
                <div class="search-wrap">
                    <span class="search-icon">⌕</span>
                    <input type="search" name="q" placeholder="Магазины, товары, услуги..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="search-input">
                </div>
                <button type="submit" class="search-btn">Найти</button>
            </form>
            <nav class="nav">
                <a href="index.php" class="nav-link <?= $page === 'home' ? 'active' : '' ?>">Главная</a>
                <a href="index.php?page=shops" class="nav-link <?= $page === 'shops' ? 'active' : '' ?>">Магазины</a>
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
        <div class="footer-pattern"></div>
        <div class="container footer-inner">
            <div class="footer-grid">
                <div class="footer-brand">
                    <strong><?= htmlspecialchars($siteConfig['name'] ?? 'ТРЦ Европа 27') ?></strong>
                    <p><?= htmlspecialchars($siteConfig['address'] ?? 'г. Липецк, ул. Стаханова, 36') ?></p>
                </div>
                <div class="footer-info">
                    <p><span class="footer-label">Режим работы:</span> <?= htmlspecialchars($siteConfig['work_hours'] ?? '9:00 - 22:00') ?></p>
                    <p><span class="footer-label">Горячая линия:</span> <a href="tel:88007707627"><?= htmlspecialchars($siteConfig['phone'] ?? '8-800-770-76-27') ?></a></p>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
