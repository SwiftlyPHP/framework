<?php declare(strict_types=1);

use PhpCsFixer\Finder;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setUsingCache(false)
    ->setFinder(
        Finder::create()->in(__DIR__)
    )
    ->setRules([
        '@PSR12' => true,

        // File layout
        'declare_strict_types' => true,
        'blank_line_after_opening_tag' => false,
        'no_closing_tag' => true,

        // Function usage
        'strict_param' => true,

        // Array usage
        'array_push' => true,
        'array_syntax' => ['syntax' => 'short'],
    ]);
