<?php
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
    'key' => 'group_fund_pension_investment_report',
    'title' => 'Investment Report',
    'fields' => array (
        array (
            'key' => 'field_fund_pension_investment_report',
            'label' => 'Investment Report',
            'name' => 'investment_report_file',
            'type' => 'file',
            'instructions' => 'Select the latest investment report PDF from media library. When set, this replaces the hardcoded report URL in the template.',
            'required' => 0,
            'return_format' => 'url',
            'library' => 'all',
            'mime_types' => 'pdf',
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
        array (
            array (
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page_fund-third.php',
            ),
        ),
    ),
    'menu_order' => 10,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
));

endif;
