<?php
/**
 * Модель магазина-арендатора
 */

namespace Europa27;

class Shop
{
    public function __construct(
        private \PDO $pdo
    ) {}

    /**
     * Список всех магазинов с фильтрацией
     */
    public function getList(?int $categoryId = null, ?int $floorId = null, int $limit = 100): array
    {
        $sql = "SELECT s.*, c.name AS category_name, f.name AS floor_name, f.number AS floor_number
                FROM shops s
                JOIN categories c ON s.category_id = c.id
                JOIN floors f ON s.floor_id = f.id
                WHERE 1=1";
        $params = [];

        if ($categoryId) {
            $sql .= " AND s.category_id = ?";
            $params[] = $categoryId;
        }
        if ($floorId) {
            $sql .= " AND s.floor_id = ?";
            $params[] = $floorId;
        }

        $sql .= " ORDER BY f.number, s.name LIMIT ?";
        $params[] = $limit;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Получить магазин по ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.*, c.name AS category_name, f.name AS floor_name, f.number AS floor_number
            FROM shops s
            JOIN categories c ON s.category_id = c.id
            JOIN floors f ON s.floor_id = f.id
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Получить магазин по slug
     */
    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT s.*, c.name AS category_name, f.name AS floor_name, f.number AS floor_number
            FROM shops s
            JOIN categories c ON s.category_id = c.id
            JOIN floors f ON s.floor_id = f.id
            WHERE s.slug = ?
        ");
        $stmt->execute([$slug]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Товары магазина
     */
    public function getProducts(int $shopId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE shop_id = ? ORDER BY category, name");
        $stmt->execute([$shopId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Обновить fulltext поле для поиска
     */
    public function updateFullText(int $shopId, string $fulltext): void
    {
        $stmt = $this->pdo->prepare("UPDATE shops SET fulltext = ? WHERE id = ?");
        $stmt->execute([$fulltext, $shopId]);
    }

    /**
     * Создать slug из названия
     */
    public static function slugify(string $name): string
    {
        $name = mb_strtolower($name);
        $name = preg_replace('/[^a-zа-яё0-9\s-]/u', '', $name);
        $name = preg_replace('/[\s-]+/', '-', trim($name));
        return $name ?: 'shop-' . uniqid();
    }
}
