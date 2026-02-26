<section class="hero">
    <div class="container">
        <h1>Добро пожаловать в ТРЦ «Европа 27»</h1>
        <p class="hero-description">Более 57 магазинов и услуг на площади 10 500 кв. м. Супермаркет с собственным производством, детские товары, электроника, фитнес и многое другое.</p>
        <a href="index.php?page=shops" class="btn btn-primary">Смотреть все магазины</a>
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
                <span class="floor-badge"><?= htmlspecialchars($s['floor_name']) ?></span>
                <?php if (!empty($s['product_count'])): ?>
                <span class="product-count-badge"><?= (int)$s['product_count'] ?></span>
                <?php endif; ?>
                <h3><?= htmlspecialchars($s['name']) ?></h3>
                <p class="shop-floor"><?= htmlspecialchars($s['category_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <p class="text-center"><a href="index.php?page=shops" class="btn btn-secondary">Все магазины</a></p>
    </div>
</section>
