<section class="section shop-detail">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Главная</a> / <a href="index.php?page=shops">Магазины</a> / <?= htmlspecialchars($shop['name']) ?>
        </nav>

        <article class="shop-article">
            <h1><?= htmlspecialchars($shop['name']) ?></h1>
            <p class="shop-meta"><?= htmlspecialchars($shop['category_name']) ?> · <?= htmlspecialchars($shop['floor_name']) ?><?= $shop['pavilion'] ? ' · Павильон ' . htmlspecialchars($shop['pavilion']) : '' ?></p>

            <?php if (!empty($shop['description'])): ?>
            <div class="shop-description"><?= nl2br(htmlspecialchars($shop['description'])) ?></div>
            <?php endif; ?>

            <?php if (!empty($shop['contact'])): ?>
            <p><strong>Контакты:</strong> <?= htmlspecialchars($shop['contact']) ?></p>
            <?php endif; ?>

            <?php if (!empty($products)): ?>
            <h2>Товары и услуги</h2>
            <ul class="product-list">
                <?php foreach ($products as $p): ?>
                <li>
                    <span class="product-name"><?= htmlspecialchars($p['name']) ?></span>
                    <?php if (!empty($p['category'])): ?> <span class="product-cat">(<?= htmlspecialchars($p['category']) ?>)</span><?php endif; ?>
                    <?php if (isset($p['price']) && $p['price']): ?> — <?= number_format((float)$p['price'], 2) ?> ₽<?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </article>
    </div>
</section>
