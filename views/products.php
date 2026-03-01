<section class="section products-page">
    <div class="container">
        <h1>Товары и услуги</h1>
        <p class="section-desc">Все товары и услуги арендаторов ТРЦ с указанием магазина и этажа</p>

        <form method="get" class="products-filters">
            <input type="hidden" name="page" value="products">
            <select name="floor">
                <option value="">Все этажи</option>
                <?php foreach ($floors ?? [] as $f): ?>
                <option value="<?= (int)$f['id'] ?>" <?= ($filterFloor ?? 0) === (int)$f['id'] ? 'selected' : '' ?>><?= htmlspecialchars($f['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="shop">
                <option value="">Все магазины</option>
                <?php foreach ($shops ?? [] as $s): ?>
                <option value="<?= (int)$s['id'] ?>" <?= ($filterShop ?? 0) === (int)$s['id'] ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="product_category">
                <option value="">Все категории товаров</option>
                <?php foreach ($productCategories ?? [] as $pc): ?>
                <option value="<?= htmlspecialchars($pc) ?>" <?= ($filterProductCategory ?? '') === $pc ? 'selected' : '' ?>><?= htmlspecialchars($pc) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Применить</button>
        </form>

        <p class="results-count" style="margin-top:1rem">Найдено товаров: <?= count($products ?? []) ?></p>

        <?php if (empty($products)): ?>
        <p class="empty">Товары не найдены. Попробуйте изменить фильтры.</p>
        <?php else: ?>
        <div class="products-table-wrap">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Товар / Услуга</th>
                        <th>Категория</th>
                        <th>Магазин</th>
                        <th>Этаж</th>
                        <th>Павильон</th>
                        <th>Цена</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><a href="index.php?page=shop&id=<?= (int)$p['shop_id'] ?>"><?= htmlspecialchars($p['name']) ?></a></td>
                        <td><?= htmlspecialchars($p['category'] ?? '—') ?></td>
                        <td><a href="index.php?page=shop&id=<?= (int)$p['shop_id'] ?>"><?= htmlspecialchars($p['shop_name']) ?></a></td>
                        <td><?= htmlspecialchars($p['floor_name']) ?></td>
                        <td><?= htmlspecialchars($p['pavilion'] ?? '—') ?></td>
                        <td><?= $p['price'] ? number_format((float)$p['price'], 2) . ' ₽' : '—' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</section>
