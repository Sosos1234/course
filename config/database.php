<?php
/**
 * Подключение к базе данных MySQL
 */

$config = require __DIR__ . '/config.php';
$db = $config['db'];

$host = trim($db['host'] ?? '127.0.0.1');
$name = trim($db['name'] ?? 'europa27');
$user = trim($db['user'] ?? '');
$pass = $db['pass'] ?? '';

$dsn = sprintf(
    'mysql:host=%s;dbname=%s;charset=%s',
    $host,
    $name,
    $db['charset'] ?? 'utf8mb4'
);

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    $msg = $e->getMessage();
    $hint = '';
    if (strpos($msg, '2002') !== false || strpos($msg, 'No such file') !== false) {
        $hint = ' Проверьте host — для InfinityFree: sqlXXX.infinityfree.com (без порта).';
    } elseif (strpos($msg, '1045') !== false || strpos($msg, 'Access denied') !== false) {
        $hint = ' Проверьте логин и пароль БД в config/config.php.';
    } elseif (strpos($msg, '1049') !== false || strpos($msg, 'Unknown database') !== false) {
        $hint = ' База не создана. Создайте БД в панели и выполните database/schema.sql в phpMyAdmin.';
    }
    header('Content-Type: text/html; charset=utf-8');
    die('<!DOCTYPE html><html><head><meta charset="utf-8"><title>Ошибка БД</title></head><body style="font-family:sans-serif;max-width:600px;margin:2rem auto;padding:1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;"><h2>Ошибка подключения к базе данных</h2><p>' . htmlspecialchars($msg) . '</p><p><strong>' . htmlspecialchars($hint) . '</strong></p><p>Отредактируйте <code>config/config.php</code> и укажите данные из панели хостинга (MySQL Databases).</p><p><a href="index.php">Повторить</a></p></body></html>');
}

return $pdo;
