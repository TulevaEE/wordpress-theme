<?php
if( function_exists('acf_add_local_field_group') ):

// Header and Hero fields
acf_add_local_field_group(array (
    'key' => 'group_fund_savings_header',
    'title' => 'Fund Header',
    'fields' => array (
        array (
            'key' => 'field_fund_savings_title',
            'label' => 'Fund Title',
            'name' => 'fund_title',
            'type' => 'text',
            'instructions' => 'The main title displayed in the hero section',
            'required' => 1,
            'default_value' => 'Tuleva Additional Investment Fund',
        ),
        array (
            'key' => 'field_fund_savings_description',
            'label' => 'Fund Description',
            'name' => 'fund_description',
            'type' => 'wysiwyg',
            'instructions' => 'Primary description text (supports HTML for bold text etc.)',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
        ),
        array (
            'key' => 'field_fund_savings_description_secondary',
            'label' => 'Secondary Description',
            'name' => 'fund_description_secondary',
            'type' => 'wysiwyg',
            'instructions' => 'Secondary description text',
            'required' => 0,
            'tabs' => 'all',
            'toolbar' => 'basic',
            'media_upload' => 0,
        ),
        array (
            'key' => 'field_fund_savings_cta_text',
            'label' => 'CTA Button Text',
            'name' => 'cta_button_text',
            'type' => 'text',
            'instructions' => 'Text for the call-to-action button',
            'required' => 0,
            'default_value' => 'Select this fund',
        ),
        array (
            'key' => 'field_fund_savings_cta_url',
            'label' => 'CTA Button URL',
            'name' => 'cta_button_url',
            'type' => 'url',
            'instructions' => 'URL for the call-to-action button',
            'required' => 0,
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page_fund-savings.php',
            ),
        ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
));

// Fund Details fields
acf_add_local_field_group(array (
    'key' => 'group_fund_savings_details',
    'title' => 'Fund Details',
    'fields' => array (
        array (
            'key' => 'field_fund_savings_isin',
            'label' => 'ISIN',
            'name' => 'fund_isin',
            'type' => 'text',
            'instructions' => 'Fund ISIN code (used for API data fetching)',
            'required' => 1,
        ),
        array (
            'key' => 'field_fund_savings_currency',
            'label' => 'Currency',
            'name' => 'fund_currency',
            'type' => 'text',
            'instructions' => 'Fund currency',
            'required' => 0,
            'default_value' => 'EUR',
        ),
        array (
            'key' => 'field_fund_savings_inception_date',
            'label' => 'Date of Inception',
            'name' => 'fund_inception_date',
            'type' => 'text',
            'instructions' => 'Fund inception date (e.g., "15th October 2019")',
            'required' => 0,
        ),
        array (
            'key' => 'field_fund_savings_management_fee',
            'label' => 'Management Fee',
            'name' => 'fund_management_fee',
            'type' => 'text',
            'instructions' => 'Management fee percentage (e.g., "0,19%")',
            'required' => 0,
        ),
        array (
            'key' => 'field_fund_savings_ongoing_charges',
            'label' => 'Ongoing Charges',
            'name' => 'fund_ongoing_charges',
            'type' => 'text',
            'instructions' => 'Ongoing charges percentage (e.g., "0,29%")',
            'required' => 0,
        ),
        array (
            'key' => 'field_fund_savings_redemption_fee',
            'label' => 'Redemption Fee and Issue Fee',
            'name' => 'fund_redemption_fee',
            'type' => 'text',
            'instructions' => 'Redemption and issue fee (e.g., "0%")',
            'required' => 0,
            'default_value' => '0%',
        ),
        array (
            'key' => 'field_fund_savings_manager_participation',
            'label' => 'Fund Manager Participation',
            'name' => 'fund_manager_participation',
            'type' => 'text',
            'instructions' => 'Number of units held by fund manager',
            'required' => 0,
            'default_value' => '0',
        ),
        array (
            'key' => 'field_fund_savings_risk_profile',
            'label' => 'Risk Profile',
            'name' => 'fund_risk_profile',
            'type' => 'text',
            'instructions' => 'Risk profile (e.g., "Aggressive", "Moderate", "Conservative")',
            'required' => 0,
        ),
        array (
            'key' => 'field_fund_savings_comparison_index',
            'label' => 'Comparison Index',
            'name' => 'fund_comparison_index',
            'type' => 'text',
            'instructions' => 'Comparison index (e.g., "100% MSCI ACWI (EUR)")',
            'required' => 0,
        ),
        array (
            'key' => 'field_fund_savings_co2_intensity',
            'label' => 'CO2 Intensity',
            'name' => 'fund_co2_intensity',
            'type' => 'text',
            'instructions' => 'CO2 intensity value (number only, e.g., "80.13")',
            'required' => 0,
        ),
    ),
    'location' => array (
        array (
            array (
                'param' => 'page_template',
                'operator' => '==',
                'value' => 'page_fund-savings.php',
            ),
        ),
    ),
    'menu_order' => 1,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'active' => 1,
));

// Document fields for this page are generated from the catalogue in
// helpers/acf/fund-documents.php, which registers them for every fund page from one
// definition. The keys TKF100's fields were registered with are pinned there.

endif;