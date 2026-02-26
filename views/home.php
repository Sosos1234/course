<section class="hero">
    <div class="container">
        <h1>Добро пожаловать в ТРЦ «Европа 27»</h1>
        <p class="hero-description">Торгово-развлекательный центр в Липецке. Супермаркет с собственным производством, детские товары, электроника, фитнес, кафе и многое другое.</p>
        <div class="hero-stats">
            <span class="stat"><strong><?= (int)($stats['shops'] ?? 0) ?></strong> магазинов</span>
            <span class="stat"><strong><?= (int)($stats['products'] ?? 0) ?></strong> товаров и услуг</span>
        </div>
        <div class="hero-actions">
            <a href="index.php?page=shops" class="btn btn-primary">Смотреть магазины</a>
            <a href="index.php?page=floors" class="btn btn-secondary">План этажей</a>
        </div>
    </div>
</section>

<section class="section categories-preview">
    <div class="container">
        <h2>Категории</h2>
        <div class="category-grid">
            <?php foreach ($categories as $cat): ?>
            <a href="index.php?page=shops&category=<?= (int)$cat['id'] ?>" class="category-card">
                <span class="category-name"><?= htmlspecialchars($cat['name']) ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section shops-preview">
    <div class="container">
        <h2>Магазины</h2>
        <div class="shop-grid">
            <?php foreach (array_slice($shops, 0, 8) as $s): ?>
            <a href="index.php?page=shop&id=<?= (int)$s['id'] ?>" class="shop-card">
                <div class="shop-card-image"><?= getShopIcon((int)$s['category_id']) ?></div>
                <div class="shop-card-badges">
                    <span class="floor-badge"><?= htmlspecialchars($s['floor_name']) ?></span>
                    <?php if (!empty($s['product_count'])): ?>
                    <span class="product-count-badge"><?= (int)$s['product_count'] ?></span>
                    <?php endif; ?>
                </div>
                <h3><?= htmlspecialchars($s['name']) ?></h3>
                <p class="shop-floor"><?= htmlspecialchars($s['category_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <p class="text-center"><a href="index.php?page=shops" class="btn btn-secondary">Все магазины</a></p>
    </div>
</section>

<?php if (!empty($news)): ?>
<section class="section news-preview">
    <div class="container">
        <h2>Новости и акции</h2>
        <div class="news-grid">
            <?php foreach ($news as $n): ?>
            <article class="news-card">
                <h3><?= htmlspecialchars($n['title']) ?></h3>
                <p><?= htmlspecialchars(mb_substr(strip_tags($n['content'] ?? ''), 0, 120)) ?>...</p>
                <?php if (!empty($n['shop_name'])): ?>
                <span class="news-shop"><?= htmlspecialchars($n['shop_name']) ?></span>
                <?php endif; ?>
                <a href="index.php?page=news" class="news-link">Подробнее →</a>
            </article>
            <?php endforeach; ?>
        </div>
        <p class="text-center"><a href="index.php?page=news" class="btn btn-secondary">Все новости</a></p>
    </div>
</section>
<?php endif; ?>

<section class="section cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Есть вопросы?</h2>
            <p>Звоните на горячую линию или посетите наш раздел контактов.</p>
            <a href="tel:88007707627" class="btn btn-primary">8-800-770-76-27</a>
            <a href="index.php?page=contacts" class="btn btn-secondary">Контакты</a>
        </div>
    </div>
</section>
