<section class="section floors-page">
    <div class="container">
        <h1>Навигация по этажам</h1>

        <?php foreach ($floors as $floor): ?>
        <?php $floorShops = $shopsByFloor[$floor['id']] ?? []; ?>
        <div class="floor-block">
            <h2 class="floor-title">
                <span class="floor-number"><?= (int)$floor['number'] ?></span>
                <?= htmlspecialchars($floor['name']) ?>
            </h2>
            <?php if (!empty($floor['description'])): ?>
            <p class="floor-desc"><?= htmlspecialchars($floor['description']) ?></p>
            <?php endif; ?>
            <?php if (empty($floorShops)): ?>
            <p class="floor-empty">На этом этаже пока нет магазинов.</p>
            <?php else: ?>
            <div class="floor-shops">
                <?php foreach ($floorShops as $s): ?>
                <a href="index.php?page=shop&id=<?= (int)$s['id'] ?>" class="floor-shop-card">
                    <span class="floor-shop-icon"><img src="<?= htmlspecialchars(getShopImage($s)) ?>" alt="" loading="lazy"></span>
                    <strong><?= htmlspecialchars($s['name']) ?></strong>
                    <span><?= htmlspecialchars($s['category_name']) ?><?= $s['pavilion'] ? ' · ' . htmlspecialchars($s['pavilion']) : '' ?></span>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>
