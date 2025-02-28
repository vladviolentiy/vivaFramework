<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        'single_quote' => true,
        'whitespace_after_comma_in_array' => [
            'ensure_single_space' => true,
        ],
        'trim_array_spaces' => true,
        'no_unset_cast' => true,
        'no_unused_imports' => true,
        'blank_line_before_statement' => true,
        'object_operator_without_whitespace' => true,
        'class_attributes_separation' => [
            'elements' => ['method' => 'one'],
        ],
        'phpdoc_trim' => true,
    ])
    ->setFinder(
        (new PhpCsFixer\Finder())->in('src')->in('tests'),
    );
