<?php

if (function_exists('xdebug_disable')) {
    xdebug_disable();
}

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'no_multiline_whitespace_before_semicolons' => true,
        'linebreak_after_opening_tag' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'strict_param' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                'src',
                'tests',
            ])
    )
;