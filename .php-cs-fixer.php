<?php

define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

$finder = PhpCsFixer\Finder::create()
    ->in([BASE_PATH . 'app', BASE_PATH . 'tests'])
    ->exclude(['Views', 'Config']);

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PSR12' => true,
    // 'strict_param' => true,
    'array_indentation'                      => true,
    'array_syntax'                           => ['syntax' => 'short'],
    'combine_consecutive_unsets'             => true,
    'multiline_whitespace_before_semicolons' => true,
    'single_quote'                           => true,
    'binary_operator_spaces'                 => [
        'operators' => [
            '=>' => 'align',
            '='  => 'align',
        ],
    ],
    // 'braces' => [
    //     'allow_single_line_closure' => true,
    // ],
    'no_extra_blank_lines'      => [
        'tokens' => [
            'curly_brace_block',
            'extra',
            // 'parenthesis_brace_block',
            // 'square_brace_block',
            'throw',
            'use',
        ],
    ],
    'cast_spaces'                                   => true,
    'class_definition'                              => ['single_line' => true],
    'concat_space'                                  => ['spacing' => 'one'],
    'function_typehint_space'                       => true,
    'single_line_comment_style'                     => true,
    'native_function_casing'                        => true,
    'no_empty_comment'                              => true,
    'no_empty_phpdoc'                               => true,
    'no_empty_statement'                            => true,
    'no_mixed_echo_print'                           => ['use' => 'echo'],
    'no_multiline_whitespace_around_double_arrow'   => true,
    'no_short_bool_cast'                            => true,
    'no_singleline_whitespace_before_semicolons'    => true,
    'no_spaces_around_offset'                       => true,
    'no_trailing_comma_in_singleline'               => true,
    'no_unneeded_control_parentheses'               => true,
    'no_unused_imports'                             => true,
    'no_whitespace_before_comma_in_array'           => true,
    'object_operator_without_whitespace'            => true,
    'phpdoc_align'                                  => true,
    'phpdoc_line_span'                              => false,
    'phpdoc_no_alias_tag'                           => true,
    'space_after_semicolon'                         => true,
    'standardize_not_equals'                        => true,
    'ternary_operator_spaces'                       => true,
    'trailing_comma_in_multiline'                   => ['elements' => ['arrays']],
    'trim_array_spaces'                             => true,
    'unary_operator_spaces'                         => true,
    'whitespace_after_comma_in_array'               => true,
    'no_superfluous_elseif'                         => true,
    'ordered_imports'                               => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
    'combine_consecutive_issets'                    => true,
    'explicit_indirect_variable'                    => true,
    'single_space_after_construct'                  => true,
    'list_syntax'                                   => true,
    'echo_tag_syntax'                               => ['format' => 'short'],
    'full_opening_tag'                              => true,
    'method_chaining_indentation'                   => true,
])
    ->setLineEnding("\n")
    ->setFinder($finder);
