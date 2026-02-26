<?php
/**
 * Модерн: простые геометрические акценты по категориям
 */
function getShopIcon(int $categoryId): string {
    $colors = ['#0f4c5c', '#1b7a8c', '#e07c24', '#64748b', '#0ea5e9', '#10b981', '#8b5cf6'];
    $i = ($categoryId - 1) % count($colors);
    $c = $colors[$i];
    $svg = '<svg viewBox="0 0 64 64" fill="none"><rect x="8" y="8" width="48" height="48" rx="12" fill="' . $c . '" opacity="0.15"/><rect x="20" y="20" width="24" height="24" rx="6" fill="' . $c . '" opacity="0.4"/></svg>';
    return '<span class="shop-icon">' . $svg . '</span>';
}
