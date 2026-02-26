<section class="section shops-list">
    <div class="container">
        <h1>Магазины</h1>

        <div class="filters">
            <form method="get" action="index.php" class="filter-form">
                <input type="hidden" name="page" value="shops">
                <select name="category" onchange="this.form.submit()">
                    <option value="">Все категории</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= ($filterCategory ?? 0) == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="floor" onchange="this.form.submit()">
                    <option value="">Все этажи</option>
                    <?php foreach ($floors as $f): ?>
                    <option value="<?= (int)$f['id'] ?>" <?= ($filterFloor ?? 0) == $f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <div class="shop-grid">
            <?php if (empty($shops)): ?>
            <p class="empty">Магазины не найдены. <a href="index.php?page=import">Импортировать данные</a></p>
            <?php else: ?>
            <?php foreach ($shops as $s): ?>
            <a href="index.php?page=shop&id=<?= (int)$s['id'] ?>" class="shop-card">
                <span class="floor-badge"><?= htmlspecialchars($s['floor_name']) ?></span>
                <?php if (!empty($s['product_count'])): ?>
                <span class="product-count-badge"><?= (int)$s['product_count'] ?> товаров</span>
                <?php endif; ?>
                <h3><?= htmlspecialchars($s['name']) ?></h3>
                <p class="shop-meta"><?= htmlspecialchars($s['category_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></p>
                <?php if (!empty($s['description'])): ?>
                <p class="shop-desc"><?= htmlspecialchars(mb_substr($s['description'], 0, 100)) ?>...</p>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
