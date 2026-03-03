<?php
/**
 * Короткий вход в админ-панель.
 * Открыть admin.php вместо index.php?page=admin
 */
$_GET['page'] = 'admin';
require __DIR__ . '/index.php';
