<?php
/**
 * Точка входа. Маршрутизация запросов.
 */

session_start();

$app = require __DIR__ . '/bootstrap.php';
$config = $app['config'];
$pdo = $app['pdo'];

$page = $_GET['page'] ?? 'home';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;

$allowedPages = ['home', 'shops', 'shop', 'search', 'admin', 'import'];
if (!in_array($page, $allowedPages, true)) {
    $page = 'home';
}

switch ($page) {
    case 'home':
        $pageData = [
            'page' => 'home',
            'pageTitle' => 'Главная',
            'categories' => $app['category']->getAll(),
            'floors' => $app['floor']->getAll(),
            'shops' => $app['shop']->getList(null, null, 12),
            'config' => $config['site'],
        ];
        break;

    case 'shops':
        $categoryId = isset($_GET['category']) ? (int) $_GET['category'] : null;
        $floorId = isset($_GET['floor']) ? (int) $_GET['floor'] : null;
        $pageData = [
            'page' => 'shops',
            'pageTitle' => 'Магазины',
            'shops' => $app['shop']->getList($categoryId, $floorId),
            'categories' => $app['category']->getAll(),
            'floors' => $app['floor']->getAll(),
            'config' => $config['site'],
            'filterCategory' => $categoryId,
            'filterFloor' => $floorId,
        ];
        break;

    case 'shop':
        if (!$id) {
            header('Location: index.php?page=shops');
            exit;
        }
        $shop = $app['shop']->getById($id);
        if (!$shop) {
            header('HTTP/1.0 404 Not Found');
            $pageData = ['page' => '404', 'pageTitle' => 'Не найдено', 'config' => $config['site']];
        } else {
            $pageData = [
                'page' => 'shop',
                'pageTitle' => $shop['name'],
                'shop' => $shop,
                'products' => $app['shop']->getProducts($id),
                'config' => $config['site'],
            ];
        }
        break;

    case 'search':
        $q = trim($_GET['q'] ?? '');
        $results = ['shops' => [], 'products' => []];
        if (mb_strlen($q) >= 2) {
            $results = $app['search']->search($q, 25);
        }
        $pageData = [
            'page' => 'search',
            'pageTitle' => 'Поиск',
            'query' => $q,
            'results' => $results,
            'categories' => $app['category']->getAll(),
            'config' => $config['site'],
        ];
        break;

    case 'admin':
        require __DIR__ . '/admin/index.php';
        exit;

    case 'import':
        require __DIR__ . '/import/run.php';
        exit;
}

if ($page !== 'admin' && $page !== 'import') {
    extract($pageData);
    require __DIR__ . '/views/layout.php';
}
