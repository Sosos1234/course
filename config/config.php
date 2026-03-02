<?php
/**
 * Конфигурация веб-сайта ТРЦ «Европа 27»
 * Портативный режим: создать файл portable.flag в корне проекта (или переменная EUROPA27_PORTABLE=1)
 */

if (getenv('EUROPA27_PORTABLE') || file_exists(__DIR__ . '/../portable.flag')) {
    return require __DIR__ . '/config.portable.php';
}

$db = [
    'host' => getenv('EUROPA27_DB_HOST') ?: '127.0.0.1',
    'name' => getenv('EUROPA27_DB_NAME') ?: 'europa27',
    'user' => getenv('EUROPA27_DB_USER') ?: 'europa27',
    'pass' => getenv('EUROPA27_DB_PASS') ?: 'mypassword',
    'charset' => 'utf8mb4',
];

return [
    'db' => $db,
    'site' => [
        'name' => 'ТРЦ Европа 27',
        'url' => 'http://localhost',
        'phone' => '8-800-770-76-27',
        'address' => 'г. Липецк, ул. Стаханова, 36',
        'work_hours' => '9:00 - 22:00',
    ],
    'admin' => [
        'login' => 'admin',
        'password' => 'europa27admin', // Сменить при развёртывании!
    ],
    'phpmorphy' => [
        'dict_path' => __DIR__ . '/../vendor/grigoryangeo/phpmorphy/dicts',
        'lang' => 'ru',
    ],
];
