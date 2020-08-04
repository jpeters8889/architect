<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ReturnTypeHintSniff;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use SlevomatCodingStandard\Sniffs\TypeHints\PropertyTypeHintSniff;
use ObjectCalisthenics\Sniffs\Classes\ForbiddenPublicPropertySniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff;
use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowEmptySniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
    */

    'preset' => 'default',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
    | "atom", "vscode".
    |
    | If you have another IDE that is not in this list but which provide an
    | url-handler, you could fill this config with a pattern like this:
    |
    | myide://open?url=file://%f&line=%l
    |
    */

    'ide' => null,

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind, that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        'src/Console/Stubs',
    ],

    'add' => [
        //  ExampleMetric::class => [
        //      ExampleInsight::class,
        //  ]
    ],

    'remove' => [
        DeclareStrictTypesSniff::class,
        TypeHintDeclarationSniff::class,
        VoidReturnFixer::class,
        ForbiddenNormalClasses::class,
        OrderedClassElementsFixer::class,
        ForbiddenPublicPropertySniff::class,
        ForbiddenTraits::class,
        ForbiddenSetterSniff::class,
        AssignmentInConditionSniff::class,
        DisallowEmptySniff::class,
        ParameterTypeHintSniff::class,
        ReturnTypeHintSniff::class,
        PropertyTypeHintSniff::class,
    ],

    'config' => [
        LineLengthSniff::class => [
            'lineLimit' => 100,
            'absoluteLimit' => 120,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 8,
        ],
    ],
];
