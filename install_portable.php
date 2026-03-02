<?php
/**
 * Установка портативной версии — создание SQLite и импорт данных.
 * Запускается автоматически при первом запуске с флешки.
 */
$baseDir = __DIR__;

if (!file_exists($baseDir . '/portable.flag')) {
    file_put_contents($baseDir . '/portable.flag', '1');
}

$dataDir = $baseDir . '/data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

$dbPath = $dataDir . '/europa27.sqlite';

if (file_exists($dbPath)) {
    $count = 0;
    try {
        $p = new PDO('sqlite:' . $dbPath);
        $count = (int) $p->query("SELECT COUNT(*) FROM shops")->fetchColumn();
    } catch (Exception $e) {}
    if ($count > 0) {
        exit('0');
    }
}

try {
    putenv('EUROPA27_PORTABLE=1');
    $pdo = new PDO('sqlite:' . $dbPath, null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec(file_get_contents($baseDir . '/database/schema_sqlite.sql'));

    require $baseDir . '/includes/shop_icons.php';
    if (file_exists($baseDir . '/vendor/autoload.php')) {
        require $baseDir . '/vendor/autoload.php';
    } else {
        require $baseDir . '/autoload.php';
    }
    $config = require $baseDir . '/config/config.portable.php';
    $morphy = new \Europa27\MorphyProcessor($config['phpmorphy']['dict_path'] ?? '', $config['phpmorphy']['lang'] ?? 'ru');
    $importer = new \Europa27\DataImporter($pdo, $morphy);

    $result = $importer->loadData($baseDir . '/import/data.json', false);
    echo $result['success'] ? '1' : '0';
} catch (Exception $e) {
    echo 'E:' . $e->getMessage();
}
