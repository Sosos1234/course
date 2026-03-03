<?php
/**
 * Установка портативной версии — создание SQLite и импорт данных.
 */
set_time_limit(30);
$baseDir = __DIR__;

$dataDir = $baseDir . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$dbPath = $dataDir . DIRECTORY_SEPARATOR . 'europa27.sqlite';

if (file_exists($dbPath)) {
    try {
        $p = new PDO('sqlite:' . $dbPath);
        if ((int) $p->query("SELECT COUNT(*) FROM shops")->fetchColumn() > 0) {
            exit('OK');
        }
    } catch (Throwable $e) {}
}

try {
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec(file_get_contents($baseDir . '/database/schema_sqlite.sql'));

    require $baseDir . '/includes/shop_icons.php';
    file_exists($baseDir . '/vendor/autoload.php') ? require $baseDir . '/vendor/autoload.php' : require $baseDir . '/autoload.php';
    $config = require $baseDir . '/config/config.portable.php';
    $morphy = new \Europa27\MorphyProcessor('', 'ru');
    $importer = new \Europa27\DataImporter($pdo, $morphy);
    $result = $importer->loadData($baseDir . '/import/data.json', false);

    echo $result['success'] ? 'OK' : 'FAIL';
} catch (Throwable $e) {
    echo 'ERR:' . $e->getMessage();
}
