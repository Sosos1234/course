<?php
namespace Europa27;

class News
{
    public function __construct(private \PDO $pdo) {}

    public function getLatest(int $limit = 5): array
    {
        $stmt = $this->pdo->prepare("
            SELECT n.*, s.name AS shop_name
            FROM news n
            LEFT JOIN shops s ON n.shop_id = s.id
            ORDER BY n.published_at DESC
            LIMIT " . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAll(int $limit = 20): array
    {
        $stmt = $this->pdo->prepare("
            SELECT n.*, s.name AS shop_name
            FROM news n
            LEFT JOIN shops s ON n.shop_id = s.id
            ORDER BY n.published_at DESC
            LIMIT " . (int) $limit
        );
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT n.*, s.name AS shop_name
            FROM news n
            LEFT JOIN shops s ON n.shop_id = s.id
            WHERE n.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
