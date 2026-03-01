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

$allowedPages = ['home', 'shops', 'shop', 'products', 'search', 'news', 'about', 'contacts', 'floors', 'admin', 'import', 'admin-shops', 'admin-products'];
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
            'news' => $app['news']->getLatest(3),
            'config' => $config['site'],
            'stats' => [
                'shops' => $pdo->query("SELECT COUNT(*) FROM shops")->fetchColumn(),
                'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
            ],
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

    case 'about':
        $pageData = [
            'page' => 'about',
            'pageTitle' => 'О центре',
            'config' => $config['site'],
            'stats' => [
                'shops' => $pdo->query("SELECT COUNT(*) FROM shops")->fetchColumn(),
                'products' => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
                'categories' => $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn(),
            ],
        ];
        break;

    case 'contacts':
        $pageData = [
            'page' => 'contacts',
            'pageTitle' => 'Контакты',
            'config' => $config['site'],
        ];
        break;

    case 'news':
        $pageData = [
            'page' => 'news',
            'pageTitle' => 'Новости и акции',
            'news' => $app['news']->getAll(15),
            'config' => $config['site'],
        ];
        break;

    case 'floors':
        $pageData = [
            'page' => 'floors',
            'pageTitle' => 'Навигация по этажам',
            'floors' => $app['floor']->getAll(),
            'shopsByFloor' => [],
            'config' => $config['site'],
        ];
        foreach ($app['floor']->getAll() as $f) {
            $pageData['shopsByFloor'][$f['id']] = $app['shop']->getList(null, $f['id']);
        }
        break;

    case 'products':
        $shopId = isset($_GET['shop']) ? (int) $_GET['shop'] : null;
        $productCategory = isset($_GET['product_category']) ? trim($_GET['product_category']) : null;
        $floorId = isset($_GET['floor']) ? (int) $_GET['floor'] : null;
        $pageData = [
            'page' => 'products',
            'pageTitle' => 'Товары и услуги',
            'products' => $app['product']->getList($shopId, $productCategory ?: null, $floorId),
            'productCategories' => $app['product']->getProductCategories(),
            'shops' => $app['shop']->getList(null, null, 200),
            'floors' => $app['floor']->getAll(),
            'config' => $config['site'],
            'filterShop' => $shopId,
            'filterProductCategory' => $productCategory,
            'filterFloor' => $floorId,
        ];
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

    case 'admin-shops':
        require __DIR__ . '/admin/shops.php';
        exit;

    case 'admin-products':
        require __DIR__ . '/admin/products.php';
        exit;

    case 'import':
        require __DIR__ . '/import/run.php';
        exit;
}

if (!in_array($page, ['admin', 'import', 'admin-shops', 'admin-products'], true)) {
    extract($pageData);
    require __DIR__ . '/views/layout.php';
}
