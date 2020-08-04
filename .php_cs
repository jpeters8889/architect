<?php

$rules = [
    '@Symfony' => true,
    'array_syntax' => ['syntax' => 'short'],
    'no_empty_comment' => false,
    'ordered_imports' => ['sort_algorithm' => 'length'],
    'phpdoc_trim_consecutive_blank_line_separation' => true,
    'yoda_style' => false,
    'declare_strict_types' => true,
    'no_superfluous_phpdoc_tags' => false,
];

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder(PhpCsFixer\Finder::create()
        ->exclude(['src/Console/Stubs']));
