<?php
/**
 * Модель товаров и услуг
 */

namespace Europa27;

class Product
{
    public function __construct(
        private \PDO $pdo
    ) {}

    /**
     * Список всех товаров с информацией о магазине и этаже
     */
    public function getList(?int $shopId = null, ?string $category = null, ?int $floorId = null, int $limit = 200): array
    {
        $sql = "SELECT p.id, p.name, p.category, p.price, p.shop_id,
                       s.name AS shop_name, s.pavilion, f.name AS floor_name, f.number AS floor_number
                FROM products p
                JOIN shops s ON p.shop_id = s.id
                JOIN floors f ON s.floor_id = f.id
                WHERE 1=1";
        $params = [];

        if ($shopId) {
            $sql .= " AND p.shop_id = ?";
            $params[] = $shopId;
        }
        if ($category !== null && $category !== '') {
            $sql .= " AND p.category = ?";
            $params[] = $category;
        }
        if ($floorId) {
            $sql .= " AND s.floor_id = ?";
            $params[] = $floorId;
        }

        $sql .= " ORDER BY f.number, s.name, p.category, p.name LIMIT " . (int) $limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Уникальные категории товаров (для фильтра)
     */
    public function getProductCategories(): array
    {
        $stmt = $this->pdo->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != '' ORDER BY category");
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    /**
     * Получить товар по ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Создать товар
     */
    public function create(int $shopId, array $data, string $fulltext = ''): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO products (shop_id, name, category, price, `fulltext`)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $shopId,
            $data['name'] ?? '',
            $data['category'] ?? null,
            !empty($data['price']) ? (float) $data['price'] : null,
            $fulltext,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Обновить товар
     */
    public function update(int $id, array $data, string $fulltext = ''): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE products SET name = ?, category = ?, price = ?, `fulltext` = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'] ?? '',
            $data['category'] ?? null,
            !empty($data['price']) ? (float) $data['price'] : null,
            $fulltext,
            $id,
        ]);
    }

    /**
     * Удалить товар
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
