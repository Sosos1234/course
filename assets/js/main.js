// ТРЦ Европа 27 — интерактивность
document.addEventListener('DOMContentLoaded', function() {
    // Поиск: фокус и анимация
    const searchInputs = document.querySelectorAll('.search-input');
    searchInputs.forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentElement?.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement?.classList.remove('focused');
        });
    });

    // Ссылки на якоря — плавный скролл
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // Карточки: лёгкий эффект при наведении (доп. интерактив)
    document.querySelectorAll('.shop-card, .category-card').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transition = 'transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease';
        });
    });
});
