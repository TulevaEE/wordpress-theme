<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_5905e89212219',
    'title' => 'Blog page',
    'fields' => array (
        array (
            'key' => 'field_5905ec7c128cf',
            'label' => 'Important articles category',
            'name' => 'important_articles_category',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'category',
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        ),
        array (
            'key' => 'field_5905ec7c821cf',
            'label' => 'Secondary articles category',
            'name' => 'secondary_articles_category',
            'type' => 'taxonomy',
            'instructions' => '',
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array (
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'taxonomy' => 'category',
            'field_type' => 'select',
            'allow_null' => 0,
            'add_term' => 0,
            'save_terms' => 0,
            'load_terms' => 0,
            'return_format' => 'id',
            'multiple' => 0,
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'post_template',
                'operator' => '==',
                'value' => 'page_blog.php',
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
        5 => 'author',
        6 => 'format',
        7 => 'featured_image',
        8 => 'categories',
        9 => 'tags',
        10 => 'send-trackbacks',
    ),
    'active' => 1,
    'description' => '',
));

endif;
