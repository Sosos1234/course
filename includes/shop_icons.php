<?php
/**
 * Картинки магазинов — тематические SVG-иллюстрации (чётко по смыслу)
 */
function getShopImage(array $shop): string {
    $name = mb_strtolower($shop['name'] ?? '');
    $cat = (int)($shop['category_id'] ?? 1);
    
    $map = [
        'супермаркет' => 'grocery', 'европа' => 'grocery',
        'детских' => 'kids', 'гипермаркет' => 'kids',
        'техник' => 'tech', 'электроник' => 'tech',
        'фитнес' => 'gym',
        'танцев' => 'dance',
        'одежд' => 'fashion', 'обув' => 'fashion',
        'кафе' => 'cafe', 'уют' => 'cafe',
        'аптек' => 'pharmacy', 'здоровье' => 'pharmacy',
    ];
    
    foreach ($map as $kw => $theme) {
        if (mb_strpos($name, $kw) !== false) {
            return shopThemeSvg($theme, $shop['name'] ?? '');
        }
    }
    
    $catTheme = [1 => 'grocery', 2 => 'fashion', 3 => 'kids', 4 => 'tech', 5 => 'store', 6 => 'gym', 7 => 'service'];
    return shopThemeSvg($catTheme[$cat] ?? 'store', $shop['name'] ?? '');
}

function shopThemeSvg(string $theme, string $title): string {
    $w = 400; $h = 250;
    $t = htmlspecialchars(mb_substr($title, 0, 25));
    
    $themes = [
        'grocery' => [
            'fill' => '#2d8b6f',
            'icon' => '<path d="M80 160h240v50H80z"/><path d="M120 120h40v40h-40z"/><path d="M200 120h40v40h-40z"/><path d="M280 120h40v40h-40z"/>',
        ],
        'fashion' => [
            'fill' => '#6c5ce7',
            'icon' => '<path d="M160 80l60 140h80l-100-140z"/><path d="M180 100h20v120h-20z"/>',
        ],
        'kids' => [
            'fill' => '#fd79a8',
            'icon' => '<circle cx="200" cy="130" r="35"/><circle cx="185" cy="125" r="5"/><circle cx="215" cy="125" r="5"/><path d="M175 150q25 15 50 0"/>',
        ],
        'tech' => [
            'fill' => '#00b894',
            'icon' => '<rect x="120" y="90" width="160" height="100" rx="5"/><path d="M120 110h160"/><circle cx="200" cy="165" r="8"/>',
        ],
        'store' => [
            'fill' => '#0984e3',
            'icon' => '<rect x="100" y="100" width="200" height="80" rx="5"/><path d="M100 100l20-30h160l20 30"/>',
        ],
        'gym' => [
            'fill' => '#e17055',
            'icon' => '<path d="M130 130h20v60h-20z"/><path d="M250 130h20v60h-20z"/><path d="M110 190h180"/><path d="M180 150v40"/>',
        ],
        'dance' => [
            'fill' => '#636e72',
            'icon' => '<circle cx="200" cy="120" r="25"/><path d="M175 180 Q200 220 225 180"/><path d="M200 95v-30"/>',
        ],
        'cafe' => [
            'fill' => '#fdcb6e',
            'icon' => '<path d="M140 140h120l20 50H120z"/><path d="M200 100v40"/><circle cx="200" cy="100" r="25" fill="none" stroke="currentColor" stroke-width="8"/>',
        ],
        'pharmacy' => [
            'fill' => '#74b9ff',
            'icon' => '<rect x="120" y="90" width="40" height="80"/><rect x="240" y="90" width="40" height="80"/><path d="M140 100h120v15H140z"/><path d="M200 105v60"/>',
        ],
        'service' => [
            'fill' => '#a29bfe',
            'icon' => '<path d="M120 120h160v80h-160z"/><path d="M120 120l80-40 80 40"/>',
        ],
    ];
    
    $th = $themes[$theme] ?? $themes['store'];
    $svg = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg" width="'.$w.'" height="'.$h.'" viewBox="0 0 '.$w.' '.$h.'">'
        . '<rect width="'.$w.'" height="'.$h.'" fill="'.$th['fill'].'"/>'
        . '<g fill="rgba(255,255,255,0.3)" stroke="rgba(255,255,255,0.5)" stroke-width="4">'.$th['icon'].'</g>'
        . '<text x="'.($w/2).'" y="'.($h-25).'" font-family="Arial,sans-serif" font-size="16" font-weight="bold" fill="white" text-anchor="middle">'.$t.'</text>'
        . '</svg>';
    
    return 'data:image/svg+xml,' . rawurlencode($svg);
}

function getShopImageFallback(array $shop): string {
    return getShopImage($shop);
}

function getShopIcon(int $categoryId): string {
    return '';
}
