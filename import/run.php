<?php
/**
 * Скрипт импорта данных из JSON
 */
session_start();
require __DIR__ . '/../bootstrap.php';

$file = __DIR__ . '/data.json';
$result = $app['importer']->loadData($file);

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Импорт данных</title>
    <style>body{font-family:sans-serif;max-width:600px;margin:2rem auto;padding:1rem;} .ok{color:green;} .err{color:red;} a{color:#1a4d6d;}</style>
</head>
<body>
    <h1>Импорт данных</h1>
    <?php if ($result['success']): ?>
    <p class="ok">Импорт выполнен успешно.</p>
    <p>Добавлено магазинов: <?= (int)($result['imported_shops'] ?? 0) ?></p>
    <p>Добавлено товаров: <?= (int)($result['imported_products'] ?? 0) ?></p>
    <?php else: ?>
    <p class="err">Ошибка импорта:</p>
    <ul>
        <?php foreach ($result['errors'] ?? [] as $err): ?>
        <li><?= htmlspecialchars($err) ?></li>
        <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <?php if (!empty($result['errors']) && $result['success']): ?>
    <p>Предупреждения:</p>
    <ul><?php foreach ($result['errors'] as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
    <?php endif; ?>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
