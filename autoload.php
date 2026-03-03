<?php
/**
 * Простой автозагрузчик классов (замена Composer при его отсутствии)
 */
spl_autoload_register(function ($class) {
    if (strpos($class, 'Europa27\\') !== 0) {
        return false;
    }
    $file = __DIR__ . '/src/' . str_replace('\\', '/', substr($class, 8)) . '.php';
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});
