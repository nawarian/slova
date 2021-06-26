<?php

declare(strict_types=1);

namespace Nawarian\Slova;

use Symfony\Component\Console\Application as SymfonyApplication;

class Application extends SymfonyApplication
{
    private const APP_NAME = 'slova';
    private const APP_VERSION = '1.0.0';

    public function __construct()
    {
        parent::__construct(self::APP_NAME, self::APP_VERSION);
    }
}
