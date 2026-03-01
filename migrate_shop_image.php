<?php
/**
 * Миграция: добавить колонку image в таблицу shops.
 * Запустить один раз: php migrate_shop_image.php
 */
$config = require __DIR__ . '/config/config.php';
$pdo = new PDO(
    "mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}",
    $config['db']['user'],
    $config['db']['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
try {
    $pdo->exec("ALTER TABLE `shops` ADD COLUMN `image` VARCHAR(255) DEFAULT NULL AFTER `contact`");
    echo "Колонка image добавлена.\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column') !== false) {
        echo "Колонка image уже существует.\n";
    } else {
        throw $e;
    }
}
