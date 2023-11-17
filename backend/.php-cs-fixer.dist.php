<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database/factories',
        __DIR__ . '/database/seeders',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        // Arrays are written as [] instead of array()
        'array_syntax' => true,
        // For simple strings, single quotes are preferred
        'single_quote' => true,
        // PHPDoc @param cannot be missing
        'phpdoc_add_missing_param_annotation' => true,
        // remove blank line spaces
        'no_whitespace_in_blank_line' => true,
        // Do not put spaces before and after ->
        'object_operator_without_whitespace' => true,
        // Space standardization for ternary operators
        'ternary_operator_spaces' => true,
        // Do not put spaces before commas in arrays
        'no_whitespace_before_comma_in_array' => true,
        // Put a space after the comma in the array
        'whitespace_after_comma_in_array' => true,
        // Do not add a comma at the end of single-row arrays
        'no_trailing_comma_in_singleline_array' => true,
        // remove unnecessary semicolon
        'no_empty_statement' => true,
        // Do not insert multiple line breaks before and after => in the array
        'no_multiline_whitespace_around_double_arrow' => true,
        // Delete unused uses
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
