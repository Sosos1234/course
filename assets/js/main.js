// ТРЦ Европа 27 — клиентский скрипт
document.addEventListener('DOMContentLoaded', function() {
    // Подсветка поисковой формы при фокусе
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('focus', function() {
            this.parentElement?.classList.add('focused');
        });
        searchInput.addEventListener('blur', function() {
            this.parentElement?.classList.remove('focused');
        });
    }
});
