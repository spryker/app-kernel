<?php

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\SetList;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;
use Rector\Php80\Rector\FunctionLike\MixedTypeRector;

return static function (RectorConfig $rectorConfig): void {

    $rectorConfig->sets([
        SetList::PHP_80
    ]);

    $rectorConfig->ruleWithConfiguration(ClassPropertyAssignToConstructorPromotionRector::class, [
        ClassPropertyAssignToConstructorPromotionRector::INLINE_PUBLIC => true,
    ]);

    $rectorConfig->skip([
        RemoveUselessParamTagRector::class,
        RemoveUnusedPromotedPropertyRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUselessVarTagRector::class,
        MixedTypeRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class => [
            '**/*Bridge.php',
            '**/*Plugin.php',
            '**/*Facade.php',
            '**/*Factory.php',
            '**/*Config.php',
            '**/*DependencyProvider.php',
            '**/*Interface.php',
            '**/*Controller.php',
        ],
    ]);
};

