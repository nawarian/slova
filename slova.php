<?php

declare(strict_types=1);

use Nawarian\Slova\Application;
use Psr\Container\ContainerInterface;

require_once __DIR__ . '/vendor/autoload.php';

/** @var ContainerInterface $di */
$di = require __DIR__ . '/di.php';

/** @var Application $app */
$app = $di->get(Application::class);
exit($app->run());
