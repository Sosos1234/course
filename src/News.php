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

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO news (title, content, shop_id, published_at)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['title'] ?? '',
            $data['content'] ?? null,
            !empty($data['shop_id']) ? (int) $data['shop_id'] : null,
            $data['published_at'] ?? date('Y-m-d H:i:s'),
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE news SET title = ?, content = ?, shop_id = ?, published_at = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'] ?? '',
            $data['content'] ?? null,
            !empty($data['shop_id']) ? (int) $data['shop_id'] : null,
            $data['published_at'] ?? date('Y-m-d H:i:s'),
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
