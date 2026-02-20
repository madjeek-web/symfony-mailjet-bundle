<?php

declare(strict_types=1);

/*
 * .php-cs-fixer.php — Code Style Configuration
 *
 * 🎨 What is PHP CS Fixer?
 * It automatically formats your PHP code to follow consistent style rules.
 * Think of it like "Prettier" for JavaScript, but for PHP.
 *
 * Run: composer cs-fix       → auto-fix all style issues
 * Run: composer cs-check     → check without fixing (for CI)
 *
 * We follow the Symfony coding standards + some extra rules.
 * See: https://cs.symfony.com/
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([__DIR__ . '/src', __DIR__ . '/tests'])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules([
        '@Symfony'                   => true,    // All Symfony coding standards
        '@Symfony:risky'             => true,    // Risky but correct Symfony rules
        '@PHP83Migration'            => true,    // PHP 8.3 specific fixes
        'declare_strict_types'       => true,    // Always add declare(strict_types=1)
        'array_syntax'               => ['syntax' => 'short'],  // Use [] instead of array()
        'ordered_imports'            => true,    // Sort use statements alphabetically
        'no_unused_imports'          => true,    // Remove unused use statements
        'trailing_comma_in_multiline'=> true,    // Trailing commas in multi-line arrays
        'blank_line_after_opening_tag' => true,
        'single_quote'               => true,    // Use 'single quotes' where possible
        'explicit_string_variable'   => true,    // Use {$var} in strings
        'fully_qualified_strict_types' => true,  // Use FQN for type hints
        'phpdoc_align'               => ['align' => 'left'],
        'phpdoc_order'               => true,    // Order PHPDoc tags consistently
        'strict_comparison'          => true,    // Use === instead of ==
        'strict_param'               => true,    // Use strict comparisons in built-in functions
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache');
