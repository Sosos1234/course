<?php
/**
 * Минималистичные иконки магазинов по категориям (модерн, геометрия)
 */
function getShopIcon(int $categoryId): string {
    $icons = [
        1 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 24H52v32H12z"/><path d="M24 24V16a8 8 0 0116 0v8"/></svg>',
        2 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M18 18h28l6 28H12z"/><path d="M22 18a10 10 0 0120 0"/></svg>',
        3 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="32" cy="38" r="12"/><path d="M28 28a4 4 0 018 0"/><circle cx="28" cy="36" r="1.5"/><circle cx="36" cy="36" r="1.5"/></svg>',
        4 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><rect x="14" y="18" width="36" height="24" rx="1"/><path d="M14 30h36"/><circle cx="32" cy="46" r="1.5"/></svg>',
        5 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M10 26h44v28H10z"/><path d="M10 26l6-10h32l6 10"/><path d="M26 26v28"/><path d="M38 26v28"/></svg>',
        6 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M18 34h8v14h-8z"/><path d="M38 34h8v14h-8z"/><path d="M12 48h40"/><path d="M32 26v22"/></svg>',
        7 => '<svg viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M18 46V22l14-6 14 6v24"/><path d="M18 22l14 6 14-6"/><path d="M32 16v12"/></svg>',
    ];
    $svg = $icons[$categoryId] ?? $icons[5];
    return '<span class="shop-icon">' . $svg . '</span>';
}
