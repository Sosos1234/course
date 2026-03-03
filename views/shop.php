<?php
$productsByCategory = [];
foreach ($products ?? [] as $p) {
    $cat = $p['category'] ?: 'Прочее';
    $productsByCategory[$cat][] = $p;
}
$hasTabs = count($productsByCategory) > 1;
?>
<section class="section shop-detail">
    <div class="container">
        <nav class="breadcrumb">
            <a href="index.php">Главная</a> / <a href="index.php?page=shops">Магазины</a> / <?= htmlspecialchars($shop['name']) ?>
        </nav>

        <article class="shop-article">
            <header class="shop-header">
                <div class="shop-header-top">
                    <div class="shop-header-image"><img src="<?= htmlspecialchars(getShopImage($shop)) ?>" alt="<?= htmlspecialchars($shop['name']) ?>"></div>
                    <div>
                        <h1><?= htmlspecialchars($shop['name']) ?></h1>
                        <div class="shop-badges">
                            <span class="badge badge-category"><?= htmlspecialchars($shop['category_name']) ?></span>
                            <span class="badge badge-floor"><?= htmlspecialchars($shop['floor_name']) ?><?= !empty($shop['pavilion']) ? ' · секция ' . htmlspecialchars($shop['pavilion']) : '' ?></span>
                        </div>
                <?php if (!empty($shop['contact'])): ?>
                        <p class="shop-contact">
                            <span class="contact-icon">📞</span>
                            <a href="tel:<?= preg_replace('/\D/', '', $shop['contact']) ?>"><?= htmlspecialchars($shop['contact']) ?></a>
                        </p>
                <?php endif; ?>
                    </div>
                </div>
            </header>

            <?php if (!empty($shop['description'])): ?>
            <div class="shop-description"><?= nl2br(htmlspecialchars($shop['description'])) ?></div>
            <?php endif; ?>

            <?php if (!empty($products)): ?>
            <div class="products-section">
                <h2><span class="products-count"><?= count($products) ?></span> товаров и услуг</h2>

                <?php if ($hasTabs): ?>
                <div class="tab-buttons">
                    <?php $i = 0; foreach ($productsByCategory as $catName => $items): ?>
                    <button type="button" class="tab-btn <?= $i === 0 ? 'active' : '' ?>" data-tab="tab-<?= $i ?>">
                        <?= htmlspecialchars($catName) ?> (<?= count($items) ?>)
                    </button>
                    <?php $i++; endforeach; ?>
                </div>
                <div class="tab-contents">
                    <?php $i = 0; foreach ($productsByCategory as $catName => $items): ?>
                    <div class="tab-content <?= $i === 0 ? 'active' : '' ?>" id="tab-<?= $i ?>">
                        <div class="product-cards">
                            <?php foreach ($items as $p): ?>
                            <?php
                            $hasPrices = false;
                            $minPrice = null;
                            if (!empty($p['variants'])) {
                                foreach ($p['variants'] as $v) {
                                    $pr = isset($v['price']) ? (float)$v['price'] : null;
                                    if ($pr !== null && $pr > 0) { $hasPrices = true; $minPrice = $minPrice === null ? $pr : min($minPrice, $pr); }
                                }
                            }
                            ?>
                            <div class="product-card product-card-modern">
                                <div class="product-card-header">
                                    <span class="product-card-icon">📦</span>
                                    <span class="product-name"><?= htmlspecialchars($p['name']) ?></span>
                                    <?php if ($minPrice !== null): ?>
                                    <span class="product-price-badge">от <?= number_format($minPrice, 0) ?> ₽</span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($p['variants'])): ?>
                                <div class="product-variants-table">
                                    <?php foreach ($p['variants'] as $v): ?>
                                    <div class="product-variant-row">
                                        <span class="variant-name"><?= htmlspecialchars($v['name']) ?></span>
                                        <span class="variant-price">
                                            <?php if (isset($v['price']) && (float)$v['price'] > 0): ?>
                                            <?= number_format((float)$v['price'], 0) ?> ₽
                                            <?php elseif (isset($v['price']) && (float)$v['price'] == 0): ?>
                                            <span class="price-free">Бесплатно</span>
                                            <?php else: ?>
                                            —
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <?php if (isset($p['price']) && $p['price'] && empty($p['variants'])): ?>
                                <span class="product-price"><?= number_format((float)$p['price'], 0) ?> ₽</span>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php $i++; endforeach; ?>
                </div>
                <?php else: ?>
                <div class="product-cards">
                    <?php foreach ($products as $p): ?>
                    <?php
                    $hasPrices = false;
                    $minPrice = null;
                    if (!empty($p['variants'])) {
                        foreach ($p['variants'] as $v) {
                            $pr = isset($v['price']) ? (float)$v['price'] : null;
                            if ($pr !== null && $pr > 0) { $hasPrices = true; $minPrice = $minPrice === null ? $pr : min($minPrice, $pr); }
                        }
                    }
                    ?>
                    <div class="product-card product-card-modern">
                        <div class="product-card-header">
                            <span class="product-card-icon">📦</span>
                            <span class="product-name"><?= htmlspecialchars($p['name']) ?></span>
                            <?php if ($minPrice !== null): ?>
                            <span class="product-price-badge">от <?= number_format($minPrice, 0) ?> ₽</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($p['variants'])): ?>
                        <div class="product-variants-table">
                            <?php foreach ($p['variants'] as $v): ?>
                            <div class="product-variant-row">
                                <span class="variant-name"><?= htmlspecialchars($v['name']) ?></span>
                                <span class="variant-price">
                                    <?php if (isset($v['price']) && (float)$v['price'] > 0): ?>
                                    <?= number_format((float)$v['price'], 0) ?> ₽
                                    <?php elseif (isset($v['price']) && (float)$v['price'] == 0): ?>
                                    <span class="price-free">Бесплатно</span>
                                    <?php else: ?>
                                    —
                                    <?php endif; ?>
                                </span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($p['category'])): ?>
                        <span class="product-cat"><?= htmlspecialchars($p['category']) ?></span>
                        <?php endif; ?>
                        <?php if (isset($p['price']) && $p['price'] && empty($p['variants'])): ?>
                        <span class="product-price"><?= number_format((float)$p['price'], 0) ?> ₽</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </article>
    </div>
</section>

<?php if ($hasTabs): ?>
<script>
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
        document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
        this.classList.add('active');
        var id = this.getAttribute('data-tab');
        var content = document.getElementById(id);
        if (content) content.classList.add('active');
    });
});
</script>
<?php endif; ?>
