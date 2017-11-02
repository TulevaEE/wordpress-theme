<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_bfef95601890a',
    'title' => 'Fund components',
    'fields' => array (
        array (
            'key' => 'field_59026d1511bbd',
            'label' => 'Components',
            'name' => 'fund_components',
            'type' => 'flexible_content',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'layouts' => array (
                '596f4d84adf41' => array (
                    'key' => '596f4d84adf41',
                    'name' => 'qa_block',
                    'label' => 'Q&A Block',
                    'display' => 'row',
                    'sub_fields' => array (
                        array (
                            'key' => 'field_596f4d9badf42',
                            'label' => 'Questions',
                            'name' => 'questions',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 1,
                            'conditional_logic' => 0,
                            'wrapper' => array (
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'collapsed' => '',
                            'min' => 0,
                            'max' => 0,
                            'layout' => 'table',
                            'button_label' => '',
                            'sub_fields' => array (
                                array (
                                    'key' => 'field_596f4dc0adf44',
                                    'label' => 'Question',
                                    'name' => 'question',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => 0,
                                    'wrapper' => array (
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'maxlength' => '',
                                ),
                                array (
                                    'key' => 'field_596f4dc8adf45',
                                    'label' => 'Answer',
                                    'name' => 'answer',
                                    'type' => 'wysiwyg',
                                    'instructions' => '',
                                    'required' => 1,
                                    'conditional_logic' => 0,
                                    'wrapper' => array (
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'tabs' => 'all',
                                    'toolbar' => 'full',
                                    'media_upload' => 1,
                                    'delay' => 0,
                                ),
                            ),
                        ),
                    ),
                    'min' => '',
                    'max' => '',
                ),
            ),
            'button_label' => 'Add component',
            'min' => '',
            'max' => '',
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page_fund-stocks.php',
            ),
        ),
        array (
            array (
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page_fund-bonds.php',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'seamless',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array (
        0 => 'the_content',
        1 => 'excerpt',
        2 => 'custom_fields',
        3 => 'discussion',
        4 => 'comments',
        5 => 'format',
        6 => 'featured_image',
        7 => 'categories',
        8 => 'tags',
        9 => 'send-trackbacks',
    ),
    'active' => 1,
    'description' => '',
));

endif;
