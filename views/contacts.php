<section class="section contacts-page">
    <div class="container">
        <h1>Контакты</h1>

        <div class="contacts-grid">
            <div class="contact-card">
                <h3>Адрес</h3>
                <p><?= htmlspecialchars($config['address'] ?? 'г. Липецк, ул. Стаханова, 36') ?></p>
            </div>
            <div class="contact-card">
                <h3>Режим работы</h3>
                <p><?= htmlspecialchars($config['work_hours'] ?? '9:00 - 22:00') ?></p>
                <p class="contact-note">Ежедневно, без выходных</p>
            </div>
            <div class="contact-card highlight">
                <h3>Горячая линия</h3>
                <p class="contact-phone"><a href="tel:88007707627"><?= htmlspecialchars($config['phone'] ?? '8-800-770-76-27') ?></a></p>
                <p class="contact-note">Бесплатно по всей России</p>
            </div>
        </div>

        <div class="contacts-info">
            <h2>Как добраться</h2>
            <p>ТРЦ «Европа 27» расположен в центральной части Липецка. Добраться можно на общественном транспорте или личном автомобиле. Рядом с центром организована парковка на 71 машино-место.</p>
        </div>
    </div>
</section>
