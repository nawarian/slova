<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Nawarian\Slova\Application;
use Nawarian\Slova\Commands\TypeItBack;
use Nawarian\Slova\Repository\Dictionary;
use Psr\Container\ContainerInterface;
use function DI\autowire;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    Dictionary::class => autowire(Dictionary::class),
    TypeItBack::class => autowire(TypeItBack::class),

    Application::class => function (ContainerInterface $container) {
        $app = new Application();

        $app->addCommands([
            $container->get(TypeItBack::class),
        ]);

        return $app;
    },
]);

return $builder->build();
