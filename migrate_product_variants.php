<?php
/**
 * Миграция: создать таблицу product_variants.
 * Запустить один раз: php migrate_product_variants.php
 */
$pdo = require __DIR__ . '/config/database.php';

$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

if ($driver === 'sqlite') {
    $sql = "CREATE TABLE IF NOT EXISTS product_variants (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        product_id INTEGER NOT NULL,
        name VARCHAR(255) NOT NULL,
        price REAL,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )";
} else {
    $sql = "CREATE TABLE IF NOT EXISTS `product_variants` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `product_id` INT UNSIGNED NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `price` DECIMAL(10,2) DEFAULT NULL,
        FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
}

try {
    $pdo->exec($sql);
    echo "Таблица product_variants создана или уже существует.\n";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
