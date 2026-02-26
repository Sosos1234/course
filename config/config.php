<?php
/**
 * Конфигурация веб-сайта ТРЦ «Европа 27»
 */

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'europa27',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
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
