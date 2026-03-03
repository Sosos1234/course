<?php
/**
 * Админ: управление новостями (список, добавление, редактирование, удаление)
 */
require __DIR__ . '/../includes/admin_auth.php';
$app = require __DIR__ . '/../bootstrap.php';

$news = $app['news'];
$shops = $app['shop']->getList(null, null, 200);

// Удаление
if (isset($_POST['delete_news']) && isset($_POST['id'])) {
    $news->delete((int) $_POST['id']);
    header('Location: index.php?page=admin-news&deleted=1');
    exit;
}

// Сохранение
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_news'])) {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'content' => trim($_POST['content'] ?? ''),
        'shop_id' => trim($_POST['shop_id'] ?? ''),
        'published_at' => trim($_POST['published_at'] ?? '') ?: date('Y-m-d H:i:s'),
    ];
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    if ($id) {
        $news->update($id, $data);
        header('Location: index.php?page=admin-news&updated=1');
    } else {
        $news->create($data);
        header('Location: index.php?page=admin-news&added=1');
    }
    exit;
}

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$addNew = isset($_GET['add']);
$newsData = null;
if ($editId) {
    $newsData = $news->getById($editId);
    if (!$newsData) { header('Location: index.php?page=admin-news'); exit; }
} elseif ($addNew) {
    $newsData = ['title' => '', 'content' => '', 'shop_id' => null, 'published_at' => date('Y-m-d H:i:s')];
}

$newsList = $news->getAll(50);
$pageTitle = $newsData ? ($editId ? 'Редактировать новость' : 'Добавить новость') : 'Новости и акции';
require __DIR__ . '/admin_header.php';
?>

<div class="admin-crud">
    <div class="admin-crud-header">
        <h1><?= $addNew && $newsData ? 'Добавить новость' : ($editId && $newsData ? 'Редактировать: ' . htmlspecialchars($newsData['title']) : 'Новости и акции') ?></h1>
        <?php if (!$addNew || !$newsData): ?>
        <a href="index.php?page=admin-news&add=1" class="btn btn-primary">+ Добавить новость</a>
        <?php endif; ?>
    </div>

    <?php if (($addNew || $editId) && $newsData): ?>
    <form method="post" class="admin-form">
        <?php if ($editId): ?><input type="hidden" name="id" value="<?= (int)$newsData['id'] ?>"><?php endif; ?>
        <input type="hidden" name="save_news" value="1">
        <div class="form-group">
            <label for="title">Заголовок *</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($newsData['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Текст новости</label>
            <textarea id="content" name="content" rows="5"><?= htmlspecialchars($newsData['content'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="shop_id">Магазин (опционально)</label>
            <select id="shop_id" name="shop_id">
                <option value="">— Без привязки —</option>
                <?php foreach ($shops as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= ($newsData['shop_id'] ?? null) == $s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="published_at">Дата публикации</label>
            <input type="datetime-local" id="published_at" name="published_at" value="<?= isset($newsData['published_at']) ? date('Y-m-d\TH:i', strtotime($newsData['published_at'])) : date('Y-m-d\TH:i') ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="index.php?page=admin-news" class="btn btn-secondary">Отмена</a>
        </div>
    </form>
    <?php else: ?>
    <?php if (isset($_GET['deleted'])): ?><p class="admin-message admin-message-success">Новость удалена.</p><?php endif; ?>
    <?php if (isset($_GET['updated'])): ?><p class="admin-message admin-message-success">Новость обновлена.</p><?php endif; ?>
    <?php if (isset($_GET['added'])): ?><p class="admin-message admin-message-success">Новость добавлена.</p><?php endif; ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Заголовок</th>
                <th>Магазин</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($newsList as $n): ?>
            <tr>
                <td><?= htmlspecialchars($n['title']) ?></td>
                <td><?= htmlspecialchars($n['shop_name'] ?? '—') ?></td>
                <td><?= date('d.m.Y', strtotime($n['published_at'])) ?></td>
                <td>
                    <a href="index.php?page=admin-news&edit=<?= (int)$n['id'] ?>" class="admin-action">Изменить</a>
                    <form method="post" style="display:inline" onsubmit="return confirm('Удалить новость?');">
                        <input type="hidden" name="id" value="<?= (int)$n['id'] ?>">
                        <input type="hidden" name="delete_news" value="1">
                        <button type="submit" class="admin-action admin-action-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php if (empty($newsList)): ?>
    <p class="admin-message">Новостей пока нет. <a href="index.php?page=admin-news&add=1">Добавить первую новость</a></p>
    <?php endif; ?>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/admin_footer.php'; ?>
