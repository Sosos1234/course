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
        <p class="empty">По запросу «<?= htmlspecialchars($query) ?>» ничего не найдено.</p>
        <p>Попробуйте:</p>
        <ul class="category-suggestions">
            <?php foreach (array_slice($categories ?? [], 0, 5) as $c): ?>
            <li><a href="index.php?page=shops&category=<?= (int)$c['id'] ?>"><?= htmlspecialchars($c['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php else: ?>
        <p class="results-count">Найдено: <?= $total ?></p>

        <?php if (!empty($results['shops'])): ?>
        <h2>Магазины</h2>
        <ul class="result-list shops">
            <?php foreach ($results['shops'] as $s): ?>
            <li><a href="index.php?page=shop&id=<?= (int)$s['id'] ?>"><?= htmlspecialchars($s['name']) ?></a> — <?= htmlspecialchars($s['floor_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php if (!empty($results['products'])): ?>
        <h2>Товары и услуги</h2>
        <ul class="result-list products">
            <?php foreach ($results['products'] as $p): ?>
            <li><a href="index.php?page=shop&id=<?= (int)$p['shop_id'] ?>"><?= htmlspecialchars($p['name']) ?></a> в <?= htmlspecialchars($p['shop_name']) ?><?= $p['price'] ? ' — ' . number_format((float)$p['price'], 2) . ' ₽' : '' ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
