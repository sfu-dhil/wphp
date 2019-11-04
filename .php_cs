<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

return PhpCsFixer\Config::create()->setRules([
        '@PhpCsFixer' => true,
        '@PSR2'        => true,

        'array_syntax' => ['syntax' => 'long'],
        'braces' => [
            'allow_single_line_closure' => true,
            'position_after_functions_and_oop_constructs' => 'same'
        ],
        'class_keyword_remove' => false,
        'concat_space' => ['spacing' => 'one'],
//        'declare_strict_types' => true,
        'elseif' => false,
        'increment_style' => ['style' => 'post'],
        'no_mixed_echo_print' => ['use' => 'echo'],
        'not_operator_with_space' => true,
        'ordered_class_elements' => ['order' => [
                'use_trait',
                'constant',
                'constant_public',
                'constant_protected',
                'constant_private',
                'public',
                'protected',
                'private',
                'property',
                'property_static',
                'property_public',
                'property_protected',
                'property_private',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'construct',
                'magic',
                'destruct',
                'method',
                'method_static',
                'method_private',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_protected',
                'method_public',
                'phpunit',
            ]
        ],
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_annotation_without_dot' => false,

        'php_unit_internal_class' => ['types' => []],
        'php_unit_test_class_requires_covers' => false,

        'simplified_null_return' => true,
        'return_type_declaration' => ['space_before' => 'one'],

    ])->setCacheFile(__DIR__. '/var/cache/php_cs.cache')
      ->setFinder($finder);
