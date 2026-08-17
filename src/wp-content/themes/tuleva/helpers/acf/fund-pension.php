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
    // Required for onboarding-service to set this field over the REST API
    // (POST /wp-json/wp/v2/pages/{id} with {"acf": {"investment_report_file": <attachment id>}}).
    // Without it ACF strips the "acf" key from the request and the write silently no-ops.
    'show_in_rest' => 1,
));

acf_add_local_field_group(array (
    'key' => 'group_fund_pension_sustainability',
    'title' => 'Sustainability',
    'fields' => array (
        array (
            'key' => 'field_fund_pension_co2_intensity',
            'label' => 'CO2 Intensity',
            'name' => 'fund_co2_intensity',
            // Text, not number: the figure is rendered verbatim and trailing zeros are
            // significant ("133.80" must not collapse to "133.8"). Matches TKF100's field.
            'type' => 'text',
            'instructions' => 'CO2 intensity value (number only, e.g. "83.68"). When set, this replaces the hardcoded figure in the template.',
            'required' => 0,
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
    'menu_order' => 11,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
    // Same REST requirement as the investment report group above.
    'show_in_rest' => 1,
));

endif;
