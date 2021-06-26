<?php

declare(strict_types=1);

namespace Nawarian\Slova\Repository;

use Nawarian\Slova\Model\Word;

class Dictionary
{
    public const WORD_TYPE_NOUN = 1;
    public const WORD_TYPE_VERB = 2;
    public const WORD_TYPE_ADJECTIVE = 3;

    private const FILES = [
        self::WORD_TYPE_NOUN => __DIR__ . '/../../resources/nouns.csv',
        self::WORD_TYPE_VERB => __DIR__ . '/../../resources/verbs.csv',
        self::WORD_TYPE_ADJECTIVE => __DIR__ . '/../../resources/adjectives.csv',
    ];

    public function randomNoun(int $maxLength): ?Word
    {
        $nouns = $this->fetchShuffledWords(self::WORD_TYPE_NOUN, $maxLength);
        $noun = array_shift($nouns);

        return new Word($noun['translations_en'], $noun['bare']);
    }

    public function readVerb(int $maxLength): ?Word
    {
        $verbs = $this->fetchShuffledWords(self::WORD_TYPE_VERB, $maxLength);
        $verb = array_shift($verbs);

        return new Word($verb['translations_en'], $verb['bare']);
    }

    private function fetchShuffledWords(int $type, int $maxLength): array
    {
        $h = fopen(self::FILES[$type], 'r');
        $header = fgetcsv($h, 0, "\t");

        $words = [];
        while ($csv = fgetcsv($h, 0, "\t")) {
            if (mb_strlen($csv[0]) <= $maxLength) {
                $words[] = array_combine($header, $csv);
            }
        }
        shuffle($words);

        return $words;
    }
}
