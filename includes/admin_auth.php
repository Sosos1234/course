<?php
/**
 * Проверка прав администратора. Таймаут 1 час неактивности.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$timeout = 60 * 60;
if (($_SESSION['admin'] ?? false) !== true) {
    header('Location: index.php?page=admin');
    exit;
}
if (isset($_SESSION['admin_login_at']) && (time() - $_SESSION['admin_login_at']) > $timeout) {
    unset($_SESSION['admin'], $_SESSION['admin_login_at']);
    header('Location: index.php?page=admin');
    exit;
}
$_SESSION['admin_login_at'] = time();
