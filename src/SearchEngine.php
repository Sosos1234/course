<?php
/**
 * Поисковый движок с поддержкой морфологии и синонимов
 */

namespace Europa27;

class SearchEngine
{
    private const STOP_WORDS = ['и', 'в', 'на', 'с', 'по', 'для', 'из', 'о', 'от', 'к', 'до', 'или', 'а', 'но', 'как', 'что', 'это', 'где', 'когда', 'купить', 'найти', 'есть'];

    /** Синонимы и родственные слова для поиска по смыслу (ключ — начало слова) */
    private const SEMANTIC_MAP = [
        'ед' => ['продукт', 'кулинария', 'бакалея', 'выпечка', 'салат', 'полуфабрикат'],
        'сладк' => ['кондитер', 'выпечка', 'печень', 'торт', 'шоколад', 'десерт'],
        'молоч' => ['молоко', 'кефир', 'йогурт', 'творог', 'сыр'],
        'хлеб' => ['булк', 'батон', 'выпечка'],
        'мяс' => ['птиц', 'колбас', 'полуфабрикат'],
        'овощ' => ['фрукт', 'зелен'],
        'игр' => ['игрушк', 'развлека', 'детск'],
        'детск' => ['малыш', 'школьн', 'ребен'],
        'ребен' => ['детск', 'малыш', 'игрушк'],
        'телефон' => ['смартфон', 'ремонт', 'экран', 'электроник'],
        'смартфон' => ['телефон', 'ремонт', 'экран'],
        'ноутбук' => ['планшет', 'компьютер', 'электроник'],
        'телевизор' => ['телек', 'бытов', 'техник'],
        'холодильник' => ['холод', 'бытов', 'техник'],
        'спорт' => ['фитнес', 'тренажер', 'тренировк', 'аэробик', 'йог'],
        'фитнес' => ['тренажер', 'тренировк', 'спорт'],
        'танц' => ['хореограф', 'зумб', 'балет'],
        'одежд' => ['обув', 'плать', 'куртк', 'джинс'],
        'обув' => ['кроссовк', 'туфл', 'ботинок'],
        'техник' => ['телевизор', 'холодильник', 'смартфон', 'бытов', 'электроник'],
    ];

    public function __construct(
        private \PDO $pdo,
        private MorphyProcessor $morphy
    ) {}

    /**
     * Поиск магазинов и товаров
     */
    public function search(string $query, int $limit = 20): array
    {
        $query = trim(preg_replace('/\s+/', ' ', $query));
        if (mb_strlen($query) < 2) {
            return ['shops' => [], 'products' => []];
        }

        $tokens = preg_split('/\s+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
        $tokens = array_filter($tokens, fn($w) => mb_strlen($w) >= 2 && !in_array(mb_strtolower($w), self::STOP_WORDS, true));
        $lemmas = $this->morphy->lemmatizeArray(array_values($tokens));

        if (empty($lemmas)) {
            $lemmas = [mb_strtolower($query)];
        }

        $expandedLemmas = $this->expandWithSemantics($lemmas);
        $booleanExpr = $this->buildBooleanExpression($expandedLemmas);
        if (empty(trim($booleanExpr))) {
            return ['shops' => [], 'products' => []];
        }

        $shops = $this->searchShops($booleanExpr, $limit);
        $products = $this->searchProducts($booleanExpr, $limit);

        return [
            'shops' => $shops,
            'products' => $products,
        ];
    }

    /**
     * Расширяет леммы синонимами и родственными словами для поиска по смыслу
     */
    private function expandWithSemantics(array $lemmas): array
    {
        $expanded = [];
        foreach ($lemmas as $lemma) {
            $lemma = preg_replace('/[^\p{L}\p{N}]/u', '', mb_strtolower($lemma));
            if (mb_strlen($lemma) < 2) continue;

            $expanded[] = $lemma;
            $lemmaShort = mb_substr($lemma, 0, 5);
            foreach (self::SEMANTIC_MAP as $key => $synonyms) {
                if (mb_strpos($lemma, $key) === 0 || mb_strpos($key, $lemma) === 0 || mb_strpos($lemmaShort, $key) === 0) {
                    $expanded = array_merge($expanded, $synonyms);
                }
            }
        }
        return array_unique(array_filter($expanded, fn($w) => mb_strlen($w) >= 2));
    }

    private function buildBooleanExpression(array $lemmas): string
    {
        if (empty($lemmas)) return '';
        $cleaned = [];
        foreach ($lemmas as $lemma) {
            $lemma = preg_replace('/[^\p{L}\p{N}]/u', '', $lemma);
            if (mb_strlen($lemma) >= 2) {
                $cleaned[] = $lemma . '*';
            }
        }
        if (empty($cleaned)) return '';
        return '(' . implode(' ', $cleaned) . ')';
    }

    private function searchShops(string $expression, int $limit): array
    {
        try {
            $sql = "SELECT s.id, s.name, s.slug, s.description, s.pavilion, s.category_id,
                           f.name AS floor_name, f.number AS floor_number,
                           MATCH(s.name, s.description, s.fulltext) AGAINST(? IN BOOLEAN MODE) AS relevance
                    FROM shops s
                    JOIN floors f ON s.floor_id = f.id
                    WHERE MATCH(s.name, s.description, s.fulltext) AGAINST(? IN BOOLEAN MODE)
                    ORDER BY relevance DESC, s.name
                    LIMIT " . (int) $limit;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$expression, $expression]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $this->searchShopsFallback($expression, $limit);
        }
    }

    private function searchShopsFallback(string $expression, int $limit): array
    {
        $words = preg_split('/[\s*()+]+/', $expression, -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter(array_map(fn($w) => trim($w, '*()'), $words), fn($w) => mb_strlen($w) >= 2);
        if (empty($words)) return [];

        $conditions = [];
        $params = [];
        foreach ($words as $w) {
            $conditions[] = "(s.name LIKE ? OR s.description LIKE ? OR s.fulltext LIKE ?)";
            $params[] = '%' . $w . '%';
            $params[] = '%' . $w . '%';
            $params[] = '%' . $w . '%';
        }

        $sql = "SELECT s.id, s.name, s.slug, s.description, s.pavilion, s.category_id, f.name AS floor_name, f.number AS floor_number
                FROM shops s JOIN floors f ON s.floor_id = f.id
                WHERE " . implode(' OR ', $conditions) . "
                ORDER BY s.name LIMIT " . (int) $limit;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function searchProducts(string $expression, int $limit): array
    {
        try {
            $sql = "SELECT p.id, p.name, p.category, p.price, p.shop_id,
                           s.name AS shop_name, s.slug AS shop_slug, s.pavilion,
                           f.name AS floor_name, f.number AS floor_number,
                           MATCH(p.name, p.category, p.fulltext) AGAINST(? IN BOOLEAN MODE) AS relevance
                    FROM products p
                    JOIN shops s ON p.shop_id = s.id
                    JOIN floors f ON s.floor_id = f.id
                    WHERE MATCH(p.name, p.category, p.fulltext) AGAINST(? IN BOOLEAN MODE)
                    ORDER BY relevance DESC, p.name
                    LIMIT " . (int) $limit;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$expression, $expression]);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return $this->searchProductsFallback($expression, $limit);
        }
    }

    private function searchProductsFallback(string $expression, int $limit): array
    {
        $words = preg_split('/[\s*()+]+/', $expression, -1, PREG_SPLIT_NO_EMPTY);
        $words = array_filter(array_map(fn($w) => trim($w, '*()'), $words), fn($w) => mb_strlen($w) >= 2);
        if (empty($words)) return [];

        $conditions = [];
        $params = [];
        foreach ($words as $w) {
            $conditions[] = "(p.name LIKE ? OR p.category LIKE ? OR p.fulltext LIKE ?)";
            $params[] = '%' . $w . '%';
            $params[] = '%' . $w . '%';
            $params[] = '%' . $w . '%';
        }
        $sql = "SELECT p.id, p.name, p.category, p.price, p.shop_id, s.name AS shop_name, s.slug AS shop_slug, s.pavilion,
                       f.name AS floor_name, f.number AS floor_number
                FROM products p
                JOIN shops s ON p.shop_id = s.id
                JOIN floors f ON s.floor_id = f.id
                WHERE " . implode(' OR ', $conditions) . "
                ORDER BY p.name LIMIT " . (int) $limit;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
