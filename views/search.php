<section class="section search-results">
    <div class="container">
        <h1>Поиск</h1>

        <form action="index.php" method="get" class="search-form-inline">
            <input type="hidden" name="page" value="search">
            <input type="search" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Введите запрос..." class="search-input" autofocus>
            <button type="submit" class="btn btn-primary">Искать</button>
        </form>

        <?php if (mb_strlen($query) < 2 && $query !== ''): ?>
        <p class="search-hint">Введите минимум 2 символа для поиска.</p>
        <?php elseif (mb_strlen($query) >= 2): ?>

        <?php $total = count($results['shops'] ?? []) + count($results['products'] ?? []); ?>

        <?php if ($total === 0): ?>
        <div class="empty">
            <p>По запросу «<strong><?= htmlspecialchars($query) ?></strong>» ничего не найдено.</p>
            <p style="margin-top:1rem">Попробуйте:</p>
        </div>
        <div class="category-suggestions" style="margin-top:1rem">
            <?php foreach (array_slice($categories ?? [], 0, 5) as $c): ?>
            <a href="index.php?page=shops&category=<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="results-count">Найдено: <?= $total ?></p>

        <?php if (!empty($results['shops'])): ?>
        <h2>Магазины</h2>
        <div class="result-grid">
            <?php foreach ($results['shops'] as $s): ?>
            <a href="index.php?page=shop&id=<?= (int)$s['id'] ?>" class="result-card">
                <span class="result-type">Магазин</span>
                <span class="result-card-icon"><img src="<?= htmlspecialchars(getShopImage($s)) ?>" alt="" loading="lazy" onerror="this.src='https://picsum.photos/seed/<?= (int)($s['id'] ?? 0) ?>r/400/200'"></span>
                <strong><?= htmlspecialchars($s['name']) ?></strong>
                <span class="result-meta"><?= htmlspecialchars($s['floor_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (!empty($results['products'])): ?>
        <h2>Товары и услуги</h2>
        <div class="result-grid">
            <?php foreach ($results['products'] as $p): ?>
            <a href="index.php?page=shop&id=<?= (int)$p['shop_id'] ?>" class="result-card">
                <span class="result-type">Товар</span>
                <strong><?= htmlspecialchars($p['name']) ?></strong>
                <span class="result-meta"><?= htmlspecialchars($p['shop_name']) ?><?= $p['price'] ? ' · ' . number_format((float)$p['price'], 2) . ' ₽' : '' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
