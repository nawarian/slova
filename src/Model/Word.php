<?php

declare(strict_types=1);

namespace Nawarian\Slova\Model;

final class Word
{
    public string $english;
    public string $russian;

    public function __construct(string $english, string $russian)
    {
        $this->english = $english;
        $this->russian = $russian;
    }
}
