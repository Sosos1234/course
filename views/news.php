<section class="section news-page">
    <div class="container">
        <h1>Новости и акции</h1>

        <?php if (empty($news)): ?>
        <p class="empty">Новостей пока нет.</p>
        <?php else: ?>
        <div class="news-list">
            <?php foreach ($news as $n): ?>
            <article class="news-item">
                <h3><?= htmlspecialchars($n['title']) ?></h3>
                <?php if (!empty($n['shop_name'])): ?>
                <span class="news-badge"><?= htmlspecialchars($n['shop_name']) ?></span>
                <?php endif; ?>
                <div class="news-content"><?= nl2br(htmlspecialchars($n['content'] ?? '')) ?></div>
                <time class="news-date"><?= date('d.m.Y', strtotime($n['published_at'])) ?></time>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
