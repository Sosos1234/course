<?php
/**
 * Инициализация приложения
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/includes/shop_icons.php';

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    require_once __DIR__ . '/autoload.php';
}

$config = require __DIR__ . '/config/config.php';
$pdo = require __DIR__ . '/config/database.php';

// Создание экземпляров моделей
$morphy = new \Europa27\MorphyProcessor(
    $config['phpmorphy']['dict_path'] ?? '',
    $config['phpmorphy']['lang'] ?? 'ru'
);

return [
    'config' => $config,
    'pdo' => $pdo,
    'morphy' => $morphy,
    'shop' => new \Europa27\Shop($pdo),
    'category' => new \Europa27\Category($pdo),
    'floor' => new \Europa27\Floor($pdo),
    'search' => new \Europa27\SearchEngine($pdo, $morphy),
    'importer' => new \Europa27\DataImporter($pdo, $morphy),
    'news' => new \Europa27\News($pdo),
];
