<?php
/**
 * Картинки магазинов — локальные SVG (всегда работают, без интернета)
 */
function getShopImage(array $shop): string {
    $cat = (int)($shop['category_id'] ?? 1);
    $name = htmlspecialchars(mb_substr($shop['name'] ?? 'Магазин', 0, 20));
    
    $gradients = [
        1 => ['#2d8b6f', '#1e6b54'],
        2 => ['#6c5ce7', '#5b4cdb'],
        3 => ['#fd79a8', '#e06897'],
        4 => ['#00b894', '#009975'],
        5 => ['#0984e3', '#0873c9'],
        6 => ['#e17055', '#c75f44'],
        7 => ['#636e72', '#4d5659'],
    ];
    [$c1, $c2] = $gradients[$cat] ?? $gradients[5];
    
    $svg = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="400" height="250" viewBox="0 0 400 250">'
        . '<defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:' . $c1 . '"/><stop offset="100%" style="stop-color:' . $c2 . '"/></linearGradient></defs>'
        . '<rect width="400" height="250" fill="url(#g)"/>'
        . '<rect x="120" y="70" width="160" height="80" rx="8" fill="rgba(255,255,255,0.2)"/>'
        . '<text x="200" y="115" font-family="Arial,sans-serif" font-size="18" font-weight="bold" fill="white" text-anchor="middle">' . $name . '</text>'
        . '</svg>';
    
    return 'data:image/svg+xml,' . rawurlencode($svg);
}

function getShopImageFallback(array $shop): string {
    return getShopImage($shop);
}

function getShopIcon(int $categoryId): string {
    return '';
}
