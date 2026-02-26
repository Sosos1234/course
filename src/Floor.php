<?php
/**
 * Модель этажа
 */

namespace Europa27;

class Floor
{
    public function __construct(
        private \PDO $pdo
    ) {}

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM floors ORDER BY number");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM floors WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getByNumber(int $number): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM floors WHERE number = ?");
        $stmt->execute([$number]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
