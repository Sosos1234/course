<?php
/**
 * Миграция: добавить 3-й этаж (для магазина Галактика).
 * Запустить один раз: php migrate_floor3.php
 */
$pdo = require __DIR__ . '/config/database.php';

$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

try {
    if ($driver === 'sqlite') {
        $r = $pdo->query("SELECT 1 FROM floors WHERE number = 3");
        if ($r && $r->fetch()) {
            echo "3-й этаж уже существует.\n";
            exit(0);
        }
        $pdo->exec("INSERT INTO floors (number, name, description) VALUES (3, '3 этаж', 'Дополнительный торговый уровень')");
    } else {
        $pdo->exec("INSERT IGNORE INTO floors (number, name, description) VALUES (3, '3 этаж', 'Дополнительный торговый уровень')");
    }
    echo "3-й этаж добавлен.\n";
} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}
