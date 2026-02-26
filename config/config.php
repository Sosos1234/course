<?php
/**
 * Конфигурация веб-сайта ТРЦ «Европа 27»
 * Переменные окружения: EUROPA27_DB_HOST, EUROPA27_DB_NAME, EUROPA27_DB_USER, EUROPA27_DB_PASS
 */

$db = [
    'host' => getenv('EUROPA27_DB_HOST') ?: 'localhost',
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
