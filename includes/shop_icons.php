<?php
/**
 * Картинки магазинов — тематические изображения (Unsplash, бесплатные)
 */
function getShopImage(array $shop): string {
    $images = [
        'супермаркет' => 'https://images.unsplash.com/photo-1604718764654-c3e2b4a2a1a1?w=600',
        'европа' => 'https://images.unsplash.com/photo-1604718764654-c3e2b4a2a1a1?w=600',
        'гипермаркет детских' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600',
        'детских товаров' => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600',
        'бытовой техники' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600',
        'электроники' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600',
        'фитнес' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600',
        'танцев' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600',
        'школа танцев' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600',
        'одежд' => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600',
        'обув' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600',
        'кафе' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600',
        'уют' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600',
        'аптек' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600',
        'здоровье' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600',
    ];
    $name = mb_strtolower($shop['name'] ?? '');
    foreach ($images as $keyword => $url) {
        if (mb_strpos($name, $keyword) !== false) {
            return $url;
        }
    }
    $cat = (int)($shop['category_id'] ?? 1);
    $fallback = [
        1 => 'https://images.unsplash.com/photo-1604718764654-c3e2b4a2a1a1?w=600',
        2 => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600',
        3 => 'https://images.unsplash.com/photo-1587654780291-39c9404d746b?w=600',
        4 => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=600',
        5 => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=600',
        6 => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=600',
        7 => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=600',
    ];
    return $fallback[$cat] ?? $fallback[1];
}

function getShopIcon(int $categoryId): string {
    return '';
}
