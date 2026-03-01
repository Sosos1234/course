<?php
/**
 * Админ: управление товарами (список, добавление, редактирование, удаление)
 */
require __DIR__ . '/../includes/admin_auth.php';
$app = require __DIR__ . '/../bootstrap.php';

$product = $app['product'];
$shop = $app['shop'];
$morphy = $app['morphy'];
$shops = $app['shop']->getList(null, null, 200);

// Удаление
if (isset($_POST['delete_product']) && isset($_POST['id'])) {
    $product->delete((int) $_POST['id']);
    $back = 'index.php?page=admin-products';
    if (!empty($_POST['shop_id'])) $back .= '&shop=' . (int)$_POST['shop_id'];
    header('Location: ' . $back . '&deleted=1');
    exit;
}

// Сохранение
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_product'])) {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'category' => trim($_POST['category'] ?? ''),
        'price' => trim($_POST['price'] ?? ''),
    ];
    $shopId = (int) ($_POST['shop_id'] ?? 0);
    $fulltextParts = [$data['name'], $data['category']];
    if ($shopId) {
        $shopRow = $shop->getById($shopId);
        if ($shopRow) {
            $fulltextParts[] = $shopRow['name'] ?? '';
            $fulltextParts[] = $shopRow['description'] ?? '';
        }
    }
    $fulltext = $morphy->prepareFullText($fulltextParts);
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $shopId = (int) ($_POST['shop_id'] ?? 0);
    if ($id) {
        $product->update($id, $data, $fulltext);
        header('Location: index.php?page=admin-products&updated=1&shop=' . $shopId);
    } else {
        if ($shopId) {
            $product->create($shopId, $data, $fulltext);
            header('Location: index.php?page=admin-products&added=1&shop=' . $shopId);
        }
    }
    exit;
}

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$addNew = isset($_GET['add']);
$filterShop = isset($_GET['shop']) ? (int) $_GET['shop'] : null;
$productData = null;
if ($editId) {
    $productData = $product->getById($editId);
    if (!$productData) { header('Location: index.php?page=admin-products'); exit; }
    $filterShop = (int) ($productData['shop_id'] ?? 0);
} elseif ($addNew) {
    $productData = ['shop_id' => $filterShop ?: (int)($shops[0]['id'] ?? 0), 'name' => '', 'category' => '', 'price' => null];
}

$products = $product->getList($filterShop ?: null, null, null, 300);
$productCategories = $product->getProductCategories();

$pageTitle = $productData ? ($editId ? 'Редактировать товар' : 'Добавить товар') : 'Товары и услуги';
require __DIR__ . '/admin_header.php';
?>

<div class="admin-crud">
    <div class="admin-crud-header">
        <h1><?= $addNew && $productData ? 'Добавить товар' : ($editId && $productData ? 'Редактировать: ' . htmlspecialchars($productData['name']) : 'Товары и услуги') ?></h1>
        <?php if (!$addNew || !$productData): ?>
        <div class="admin-crud-actions">
            <form method="get" class="admin-filter-form">
                <input type="hidden" name="page" value="admin-products">
                <select name="shop" onchange="this.form.submit()">
                    <option value="">Все магазины</option>
                    <?php foreach ($shops as $s): ?>
                    <option value="<?= (int)$s['id'] ?>" <?= $filterShop == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
            <a href="index.php?page=admin-products&add=1<?= $filterShop ? '&shop=' . $filterShop : '' ?>" class="btn btn-primary">+ Добавить товар</a>
        </div>
        <?php endif; ?>
    </div>

    <?php if (($addNew || $editId) && $productData): ?>
    <form method="post" class="admin-form">
        <?php if ($editId): ?>
        <input type="hidden" name="id" value="<?= (int)$productData['id'] ?>">
        <input type="hidden" name="shop_id" value="<?= (int)$productData['shop_id'] ?>">
        <?php endif; ?>
        <input type="hidden" name="save_product" value="1">
        <?php if ($addNew): ?>
        <div class="form-group">
            <label>Магазин *</label>
            <select name="shop_id" required>
                <?php foreach ($shops as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= ($productData['shop_id'] ?? 0) == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
        <div class="form-group">
            <label for="name">Название *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($productData['name'] ?? '') ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category">Категория</label>
                <input type="text" id="category" name="category" list="categories-list" value="<?= htmlspecialchars($productData['category'] ?? '') ?>">
                <datalist id="categories-list">
                    <?php foreach ($productCategories as $pc): ?>
                    <option value="<?= htmlspecialchars($pc) ?>">
                    <?php endforeach; ?>
                </datalist>
            </div>
            <div class="form-group">
                <label for="price">Цена (₽)</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($productData['price'] ?? '') ?>">
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php?page=admin-products<?= $filterShop ? '&shop=' . $filterShop : '' ?>" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
    <?php else: ?>
    <?php if (isset($_GET['deleted'])): ?><p class="admin-message admin-message-success">Товар удалён.</p><?php endif; ?>
    <?php if (isset($_GET['updated'])): ?><p class="admin-message admin-message-success">Товар обновлён.</p><?php endif; ?>
    <?php if (isset($_GET['added'])): ?><p class="admin-message admin-message-success">Товар добавлен.</p><?php endif; ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Цена</th>
                <th>Магазин</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= htmlspecialchars($p['category'] ?? '') ?></td>
                <td><?= $p['price'] ? number_format((float)$p['price'], 2) . ' ₽' : '—' ?></td>
                <td><a href="index.php?page=admin-shops&edit=<?= (int)$p['shop_id'] ?>"><?= htmlspecialchars($p['shop_name'] ?? '') ?></a></td>
                <td>
                    <a href="index.php?page=admin-products&edit=<?= (int)$p['id'] ?>" class="admin-action">Изменить</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Удалить товар?');">
                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                        <input type="hidden" name="shop_id" value="<?= (int)($p['shop_id'] ?? 0) ?>">
                        <input type="hidden" name="delete_product" value="1">
                        <button type="submit" class="admin-action admin-action-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($products)): ?>
    <p class="admin-message">Товаров пока нет. <a href="index.php?page=admin-products&add=1">Добавить первый товар</a> (выберите магазин).</p>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
