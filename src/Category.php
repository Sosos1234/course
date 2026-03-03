<?php
/**
 * Модель категории магазинов
 */

namespace Europa27;

class Category
{
    public function __construct(
        private \PDO $pdo
    ) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM categories ORDER BY sort_order, name");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getShopCount(int $categoryId): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM shops WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }
}
