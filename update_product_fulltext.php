<?php
/**
 * Обновить поле fulltext у товаров (добавить название и описание магазина для поиска по смыслу).
 * Запустить один раз: php update_product_fulltext.php
 */
$app = require __DIR__ . '/bootstrap.php';

$pdo = $app['pdo'];
$morphy = $app['morphy'];

$stmt = $pdo->query("SELECT p.id, p.name, p.category, s.name AS shop_name, s.description AS shop_description 
                     FROM products p JOIN shops s ON p.shop_id = s.id");
$updated = 0;
while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
    $fulltext = $morphy->prepareFullText([
        $row['name'],
        $row['category'] ?? '',
        $row['shop_name'] ?? '',
        $row['shop_description'] ?? '',
    ]);
    $up = $pdo->prepare("UPDATE products SET products.`fulltext` = ? WHERE products.id = ?");
    $up->execute([$fulltext, $row['id']]);
    $updated += $up->rowCount();
}
echo "Обновлено товаров: $updated\n";
