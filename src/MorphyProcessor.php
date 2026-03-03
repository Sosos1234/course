<?php
/**
 * Модуль векторизации и морфологической обработки текста.
 * Использует phpMorphy для приведения слов к нормальной форме (лемматизация).
 */

namespace Europa27;

class MorphyProcessor
{
    private ?object $morphy = null;
    private bool $available = false;

    public function __construct(string $dictPath = '', string $lang = 'ru')
    {
        if (class_exists('phpMorphy')) {
            try {
                $path = $dictPath ?: __DIR__ . '/../vendor/grigoryangeo/phpmorphy/dicts';
                if (is_dir($path)) {
                    $this->morphy = new \phpMorphy($path, $lang);
                    $this->available = true;
                }
            } catch (\Exception $e) {
                $this->available = false;
            }
        }
    }

    /**
     * Возвращает нормальную форму слова (лемму)
     */
    public function getBaseForm(string $word): string
    {
        if (!$this->available || !$this->morphy) {
            return mb_strtolower(trim($word));
        }
        try {
            $base = $this->morphy->getBaseForm(mb_strtoupper($word));
            return $base ? mb_strtolower($base[0]) : mb_strtolower(trim($word));
        } catch (\Exception $e) {
            return mb_strtolower(trim($word));
        }
    }

    /**
     * Возвращает все словоформы
     */
    public function getAllForms(string $word): array
    {
        if (!$this->available || !$this->morphy) {
            return [mb_strtolower(trim($word))];
        }
        try {
            $forms = $this->morphy->getAllForms(mb_strtoupper($word));
            return $forms ? array_map('mb_strtolower', $forms) : [mb_strtolower(trim($word))];
        } catch (\Exception $e) {
            return [mb_strtolower(trim($word))];
        }
    }

    /**
     * Лемматизация массива слов
     */
    public function lemmatizeArray(array $words): array
    {
        $result = [];
        foreach ($words as $word) {
            $lemma = $this->getBaseForm((string) $word);
            if (mb_strlen($lemma) >= 2) {
                $result[] = $lemma;
            }
        }
        return array_unique($result);
    }

    /**
     * Подготовка текста для полнотекстового индекса
     */
    public function prepareFullText(array $fields): string
    {
        $text = implode(' ', array_filter($fields));
        $words = preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $lemmas = $this->lemmatizeArray($words);
        return implode(' ', $lemmas);
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }
}
