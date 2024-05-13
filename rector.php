<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return RectorConfig::configure()
    ->withSkip([
        __DIR__ . '/vendor',
        __DIR__ . '/Model/map',
        __DIR__ . '/Model/om',
        __DIR__ . '/var/cache',
        __DIR__ . '/Tests/Resources/cache',
    ])
    ->withPaths([
        __DIR__,
    ])
    ->withSets([
        LevelSetList::UP_TO_PHP_81,
        SymfonySetList::SYMFONY_41,
        SymfonySetList::SYMFONY_42,
        SymfonySetList::SYMFONY_43,
        SymfonySetList::SYMFONY_44,
        SymfonySetList::SYMFONY_50,
        SymfonySetList::SYMFONY_51,
        SymfonySetList::SYMFONY_52,
        SymfonySetList::SYMFONY_53,
        SymfonySetList::SYMFONY_54,
    ]);
