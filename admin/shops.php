<?php
/**
 * Админ: управление магазинами (список, добавление, редактирование, удаление)
 */
require __DIR__ . '/../includes/admin_auth.php';
$app = require __DIR__ . '/../bootstrap.php';

$shop = $app['shop'];
$morphy = $app['morphy'];
$categories = $app['category']->getAll();
$floors = $app['floor']->getAll();

// Удаление
if (isset($_POST['delete_shop']) && isset($_POST['id'])) {
    $shop->delete((int) $_POST['id']);
    header('Location: index.php?page=admin-shops&deleted=1');
    exit;
}

// Загрузка картинки
$uploadDir = __DIR__ . '/../assets/images/shops/';
$allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

function saveShopImage(array $file, string $uploadDir, array $allowedExt, int $shopId): ?string {
    if (($file['error'] ?? 0) !== UPLOAD_ERR_OK || empty($file['tmp_name'])) return null;
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mime, $allowedMime, true)) return null;
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'][$mime] ?? 'jpg';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $filename = 'shop_' . $shopId . '_' . substr(uniqid(), -8) . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) return $filename;
    return null;
}

// Сохранение (создание или обновление)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_shop'])) {
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'floor_id' => (int) ($_POST['floor_id'] ?? 0),
        'pavilion' => trim($_POST['pavilion'] ?? ''),
        'contact' => trim($_POST['contact'] ?? ''),
        'image' => null,
    ];
    $fulltext = $morphy->prepareFullText([$data['name'], $data['description']]);
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id) {
        $current = $shop->getById($id);
        $data['image'] = $current['image'] ?? null;
        $shop->update($id, $data, $fulltext);
        if (isset($_POST['remove_image']) && $_POST['remove_image'] === '1') {
            $shop->updateImage($id, null);
        } elseif (!empty($_FILES['image']['tmp_name'])) {
            $uploaded = saveShopImage($_FILES['image'], $uploadDir, $allowedExt, $id);
            if ($uploaded) $shop->updateImage($id, $uploaded);
        }
        header('Location: index.php?page=admin-shops&updated=1');
    } else {
        $newId = $shop->create($data, $fulltext);
        if (!empty($_FILES['image']['tmp_name'])) {
            $uploaded = saveShopImage($_FILES['image'], $uploadDir, $allowedExt, $newId);
            if ($uploaded) $shop->updateImage($newId, $uploaded);
        }
        header('Location: index.php?page=admin-shops&added=1');
    }
    exit;
}

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$addNew = isset($_GET['add']);
$shopData = null;
if ($editId) {
    $shopData = $shop->getById($editId);
    if (!$shopData) { header('Location: index.php?page=admin-shops'); exit; }
}
$shops = $shop->getList(null, null, 200);

$pageTitle = $shopData ? 'Редактировать магазин' : ($addNew ? 'Добавить магазин' : 'Магазины');
require __DIR__ . '/admin_header.php';
?>

<div class="admin-crud">
    <div class="admin-crud-header">
        <h1><?= $addNew || $shopData ? '' : 'Магазины' ?><?= $addNew ? 'Добавить магазин' : ($shopData ? 'Редактировать: ' . htmlspecialchars($shopData['name']) : '') ?></h1>
        <?php if (!$addNew && !$shopData): ?>
        <a href="index.php?page=admin-shops&add=1" class="btn btn-primary">+ Добавить магазин</a>
        <?php endif; ?>
    </div>

    <?php if ($addNew || $shopData): ?>
    <form method="post" enctype="multipart/form-data" class="admin-form">
        <?php if ($shopData): ?><input type="hidden" name="id" value="<?= (int)$shopData['id'] ?>"><?php endif; ?>
        <input type="hidden" name="save_shop" value="1">
        <div class="form-group">
            <label for="name">Название *</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($shopData['name'] ?? $_POST['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Описание</label>
            <textarea id="description" name="description" rows="3"><?= htmlspecialchars($shopData['description'] ?? $_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="category_id">Категория *</label>
                <select id="category_id" name="category_id" required>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($shopData['category_id'] ?? 0) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="floor_id">Этаж *</label>
                <select id="floor_id" name="floor_id" required>
                    <?php foreach ($floors as $f): ?>
                    <option value="<?= (int)$f['id'] ?>" <?= ($shopData['floor_id'] ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="pavilion">Павильон</label>
                <input type="text" id="pavilion" name="pavilion" value="<?= htmlspecialchars($shopData['pavilion'] ?? $_POST['pavilion'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="contact">Контакт</label>
                <input type="text" id="contact" name="contact" value="<?= htmlspecialchars($shopData['contact'] ?? $_POST['contact'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="image">Изображение магазина</label>
            <?php if (!empty($shopData['image'])): ?>
            <div class="admin-image-preview">
                <img src="assets/images/shops/<?= htmlspecialchars($shopData['image']) ?>" alt="" style="max-width:200px;max-height:120px;object-fit:cover;border-radius:8px;">
                <p class="admin-image-hint">Текущее фото. Загрузите новое, чтобы заменить.</p>
            </div>
            <?php endif; ?>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <?php if (!empty($shopData['image'])): ?>
            <label class="admin-checkbox-label"><input type="checkbox" name="remove_image" value="1"> Удалить текущее фото</label>
            <?php endif; ?>
            <p class="admin-field-hint">JPG, PNG, GIF или WebP. Рекомендуемый размер: 400×250 px.</p>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php?page=admin-shops" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
    <?php else: ?>
    <?php if (isset($_GET['deleted'])): ?><p class="admin-message admin-message-success">Магазин удалён.</p><?php endif; ?>
    <?php if (isset($_GET['updated'])): ?><p class="admin-message admin-message-success">Магазин обновлён.</p><?php endif; ?>
    <?php if (isset($_GET['added'])): ?><p class="admin-message admin-message-success">Магазин добавлен.</p><?php endif; ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Категория</th>
                <th>Этаж</th>
                <th>Павильон</th>
                <th>Товаров</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($shops as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td><?= htmlspecialchars($s['category_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($s['floor_name'] ?? '') ?></td>
                <td><?= htmlspecialchars($s['pavilion'] ?? '') ?></td>
                <td><?= (int)($s['product_count'] ?? 0) ?></td>
                <td>
                    <a href="index.php?page=admin-shops&edit=<?= (int)$s['id'] ?>" class="admin-action">Изменить</a>
                    <a href="index.php?page=admin-products&shop=<?= (int)$s['id'] ?>" class="admin-action">Товары</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Удалить магазин и все его товары?');">
                        <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                        <input type="hidden" name="delete_shop" value="1">
                        <button type="submit" class="admin-action admin-action-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
