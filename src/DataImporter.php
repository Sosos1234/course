<?php
/**
 * Модуль импорта данных из JSON
 */

namespace Europa27;

class DataImporter
{
    public function __construct(
        private \PDO $pdo,
        private MorphyProcessor $morphy
    ) {}

    /**
     * Загрузка данных из JSON-файла
     * @param bool $clearExisting Удалить существующие магазины и товары перед импортом
     */
    public function loadData(string $filePath, bool $clearExisting = false): array
    {
        $errors = [];
        if (!file_exists($filePath)) {
            return ['success' => false, 'errors' => ["Файл не найден: $filePath"]];
        }

        $json = file_get_contents($filePath);
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return ['success' => false, 'errors' => ['Ошибка парсинга JSON: ' . json_last_error_msg()]];
        }

        $shops = $data['shops'] ?? [];
        if (empty($shops)) {
            return ['success' => false, 'errors' => ['Нет данных о магазинах']];
        }

        try {
            if ($clearExisting) {
                $driver = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
                if ($driver === 'sqlite') {
                    $this->pdo->exec('PRAGMA foreign_keys = OFF');
                    $this->pdo->exec('DELETE FROM products');
                    $this->pdo->exec('DELETE FROM shops');
                    $this->pdo->exec('PRAGMA foreign_keys = ON');
                } else {
                    $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
                    $this->pdo->exec('TRUNCATE TABLE products');
                    $this->pdo->exec('TRUNCATE TABLE shops');
                    $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
                }
            }
            $this->pdo->beginTransaction();
            $importedShops = 0;
            $importedProducts = 0;

            foreach ($shops as $shopData) {
                if (empty($shopData['name']) || empty($shopData['category_id']) || empty($shopData['floor_id'])) {
                    $errors[] = "Пропущен магазин: неверные данные";
                    continue;
                }

                $slug = Shop::slugify($shopData['name']) . '-' . uniqid();
                $fulltext = $this->prepareTextRepresentation($shopData);

                $stmt = $this->pdo->prepare("
                    INSERT INTO shops (name, slug, description, category_id, floor_id, pavilion, contact, `fulltext`)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $shopData['name'],
                    $slug,
                    $shopData['description'] ?? null,
                    $shopData['category_id'],
                    $shopData['floor_id'],
                    $shopData['pavilion'] ?? null,
                    $shopData['contact'] ?? null,
                    $fulltext,
                ]);
                $shopId = (int) $this->pdo->lastInsertId();
                $importedShops++;

                foreach ($shopData['products'] ?? [] as $product) {
                    $pName = $product['name'] ?? '';
                    if (empty($pName)) continue;

                    $pFulltext = $this->morphy->prepareFullText([
                        $pName,
                        $product['category'] ?? '',
                        $shopData['name'] ?? '',
                        $shopData['description'] ?? '',
                    ]);

                    $stmt = $this->pdo->prepare("
                        INSERT INTO products (shop_id, name, category, price, `fulltext`)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $shopId,
                        $pName,
                        $product['category'] ?? null,
                        $product['price'] ?? null,
                        $pFulltext,
                    ]);
                    $importedProducts++;
                }
            }

            $this->pdo->commit();
            return [
                'success' => true,
                'imported_shops' => $importedShops,
                'imported_products' => $importedProducts,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return ['success' => false, 'errors' => [$e->getMessage()]];
        }
    }

    private function prepareTextRepresentation(array $shop): string
    {
        $parts = [
            $shop['name'] ?? '',
            $shop['description'] ?? '',
        ];
        return $this->morphy->prepareFullText($parts);
    }
}
