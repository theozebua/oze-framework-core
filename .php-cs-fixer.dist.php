<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

require __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()
    ->in(__DIR__);

return (new Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setFinder(
        Finder::create()
            ->in(__DIR__),
    )
    ->setRules([
        '@PSR2' => true,
        'declare_strict_types' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'trailing_comma_in_multiline' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => [
            'imports_order' => [
                'class',
                'function',
                'const',
            ],
            'sort_algorithm' => 'alpha',
        ],
        'compact_nullable_type_declaration' => true,
        'lowercase_cast' => true,
        'ordered_interfaces' => true,
        'ordered_types' => true,
        'no_unused_imports' => true,
        'no_leading_import_slash' => true,
        'no_whitespace_in_blank_line' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_after_namespace' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'case',
                'continue',
                'declare',
                'default',
                'do',
                'exit',
                'for',
                'foreach',
                'goto',
                'if',
                'include',
                'include_once',
                'phpdoc',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
                'yield_from',
            ],
        ],
        'blank_line_between_import_groups' => true,
        'blank_lines_before_namespace' => true,
        'cast_spaces' => true,
        'return_type_declaration' => true,
        'short_scalar_cast' => true,
        'single_trait_insert_per_statement' => true,
        'ternary_operator_spaces' => true,
        'visibility_required' => [
            'elements' => [
                'const',
                'method',
                'property',
            ],
        ],
        'fully_qualified_strict_types' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
    ])
    ->setRiskyAllowed(true)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
