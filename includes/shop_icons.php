<?php
/**
 * Картинки магазинов — локальные фото в assets/images/shops/
 */
function getShopImage(array $shop): string {
    $id = (int)($shop['id'] ?? 0);
    $n = $id ? (($id - 1) % 9) + 1 : 1;
    return "assets/images/shops/shop{$n}.jpg";
}

function getShopImageFallback(array $shop): string {
    return getShopImage($shop);
}

function getShopIcon(int $categoryId): string {
    return '';
}
