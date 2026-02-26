<?php
/**
 * Миграция: сократить до 2 этажей.
 * Запустить: php migrate_floors.php
 */
$config = require __DIR__ . '/config/config.php';
$db = $config['db'];

$dsn = "mysql:host={$db['host']};dbname={$db['name']};charset={$db['charset']}";
try {
    $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    
    $pdo->exec("UPDATE shops SET floor_id = 1 WHERE floor_id = 2");
    $pdo->exec("UPDATE shops SET floor_id = 2 WHERE floor_id IN (3, 4, 5, 6)");
    $pdo->exec("DELETE FROM floors WHERE id > 2");
    $pdo->exec("UPDATE floors SET number = 1, name = '1 этаж', description = 'Основной торговый уровень' WHERE id = 1");
    $pdo->exec("UPDATE floors SET number = 2, name = '2 этаж', description = 'Торговая галерея, услуги, развлечения' WHERE id = 2");
    
    echo "Миграция завершена. Теперь 2 этажа.\n";
} catch (PDOException $e) {
    die("Ошибка: " . $e->getMessage() . "\n");
}
