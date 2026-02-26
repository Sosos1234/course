<?php
/**
 * Установка БД. Выполнить один раз перед первым запуском.
 * Требует: MySQL с пустым подключением (без указания БД) или с существующей БД.
 */
$config = require __DIR__ . '/config/config.php';
$db = $config['db'];

// Создание БД
$dsn = "mysql:host={$db['host']};charset={$db['charset']}";
try {
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$db['name']}`");
    $pdo->exec(file_get_contents(__DIR__ . '/database/schema.sql'));
    if (file_exists(__DIR__ . '/database/seed_news.sql')) {
        $pdo->exec(file_get_contents(__DIR__ . '/database/seed_news.sql'));
    }
    echo "База данных успешно создана и настроена.\n";
} catch (PDOException $e) {
    die("Ошибка: " . $e->getMessage() . "\n");
}
