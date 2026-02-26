<?php
/**
 * Картинки магазинов — тематические фото по смыслу
 * shop1=продукты, shop2=одежда, shop3=дети, shop4=электроника,
 * shop5=галерея, shop6=фитнес, shop7=услуги, shop8=кафе, shop9=аптека
 */
function getShopImage(array $shop): string {
    $name = mb_strtolower($shop['name'] ?? '');
    $cat = (int)($shop['category_id'] ?? 1);
    
    if (mb_strpos($name, 'супермаркет') !== false || mb_strpos($name, 'европа') !== false) {
        return 'assets/images/shops/shop1.jpg'; // Продукты
    }
    if (mb_strpos($name, 'детских') !== false || mb_strpos($name, 'гипермаркет') !== false) {
        return 'assets/images/shops/shop3.jpg'; // Детские товары
    }
    if (mb_strpos($name, 'техник') !== false || mb_strpos($name, 'электроник') !== false) {
        return 'assets/images/shops/shop4.jpg'; // Электроника
    }
    if (mb_strpos($name, 'фитнес') !== false) {
        return 'assets/images/shops/shop6.jpg'; // Спорт
    }
    if (mb_strpos($name, 'танцев') !== false) {
        return 'assets/images/shops/shop7.jpg'; // Образование
    }
    if (mb_strpos($name, 'одежд') !== false) {
        return 'assets/images/shops/shop2.jpg'; // Одежда
    }
    if (mb_strpos($name, 'обув') !== false) {
        return 'assets/images/shops/shop2.jpg'; // Обувь
    }
    if (mb_strpos($name, 'кафе') !== false || mb_strpos($name, 'уют') !== false) {
        return 'assets/images/shops/shop8.jpg'; // Кафе
    }
    if (mb_strpos($name, 'аптек') !== false || mb_strpos($name, 'здоровье') !== false) {
        return 'assets/images/shops/shop9.jpg'; // Аптека
    }
    
    $catToImg = [
        1 => 'shop1.jpg',
        2 => 'shop2.jpg',
        3 => 'shop3.jpg',
        4 => 'shop4.jpg',
        5 => 'shop5.jpg',
        6 => 'shop6.jpg',
        7 => 'shop7.jpg',
    ];
    $file = $catToImg[$cat] ?? 'shop1.jpg';
    return 'assets/images/shops/' . $file;
}

function getShopImageFallback(array $shop): string {
    return getShopImage($shop);
}

function getShopIcon(int $categoryId): string {
    return '';
}
