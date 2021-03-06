<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Nawarian\Slova\Application;
use Nawarian\Slova\Commands\Game\ListenAndType;
use Nawarian\Slova\Commands\Game\TypeItBack;
use Nawarian\Slova\Repository\Dictionary;
use Psr\Container\ContainerInterface;
use function DI\autowire;

$builder = new ContainerBuilder();
$builder->addDefinitions([
    Dictionary::class => autowire(Dictionary::class),
    TypeItBack::class => autowire(TypeItBack::class),
    ListenAndType::class => autowire(ListenAndType::class),

    Application::class => function (ContainerInterface $container) {
        $app = new Application();

        $app->addCommands([
            $container->get(TypeItBack::class),
            $container->get(ListenAndType::class),
        ]);

        return $app;
    },
]);

return $builder->build();
