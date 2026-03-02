<?php
/**
 * Конфигурация для портативной версии (флешка) — SQLite, без MySQL
 */
$baseDir = dirname(__DIR__);
$dataDir = $baseDir . DIRECTORY_SEPARATOR . 'data';
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

return [
    'db' => [
        'driver' => 'sqlite',
        'path' => $dataDir . DIRECTORY_SEPARATOR . 'europa27.sqlite',
    ],
    'site' => [
        'name' => 'ТРЦ Европа 27',
        'url' => 'http://localhost:8000',
        'phone' => '8-800-770-76-27',
        'address' => 'г. Липецк, ул. Стаханова, 36',
        'work_hours' => '9:00 - 22:00',
    ],
    'admin' => [
        'login' => 'admin',
        'password' => 'europa27admin',
    ],
    'phpmorphy' => [
        'dict_path' => $baseDir . '/vendor/grigoryangeo/phpmorphy/dicts',
        'lang' => 'ru',
    ],
];
