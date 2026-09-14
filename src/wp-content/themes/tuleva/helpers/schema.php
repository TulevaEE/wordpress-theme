<?php

/**
 * Adds the organization details Yoast SEO stores but only outputs in its premium
 * version (legal name, founding date, description) to the Organization node.
 * @param  array $organization Yoast's Organization schema node
 * @param  array $details      Values keyed by schema.org property name
 * @return array               Organization node with the non-empty details added
 */
function add_organization_details(array $organization, array $details): array
{
    foreach ($details as $property => $value) {
        if (!empty($value)) {
            $organization[$property] = $value;
        }
    }

    return $organization;
}

/**
 * Builds FAQPage JSON-LD for a list of question/answer pairs
 * @param  array $questions Rows with 'question' and 'answer' keys, answers may contain HTML
 * @return string           JSON-LD, or an empty string when there are no questions
 */
function faq_schema_json(array $questions): string
{
    if (empty($questions)) {
        return '';
    }

    $entities = array_map(function ($row) {
        return [
            '@type' => 'Question',
            'name' => trim(strip_tags($row['question'])),
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => trim($row['answer'])],
        ];
    }, $questions);

    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_values($entities),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);
}

/**
 * Builds InvestmentFund JSON-LD so the fee figures shown on a fund page are also
 * machine-readable on that same page
 * @param  array $fund name, url, isin, provider, fees (one sentence) and
 *                     description (one sentence with the other fund details)
 * @return string      JSON-LD, or an empty string without an ISIN
 */
function fund_schema_json(array $fund): string
{
    if (empty($fund['isin'])) {
        return '';
    }

    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'InvestmentFund',
        'name' => $fund['name'],
        'url' => $fund['url'],
        'identifier' => ['@type' => 'PropertyValue', 'propertyID' => 'ISIN', 'value' => $fund['isin']],
        'provider' => ['@type' => 'Organization', 'name' => $fund['provider']],
        'feesAndCommissionsSpecification' => $fund['fees'],
        'description' => $fund['description'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP);
}

/**
 * Words the fund facts for structured data: the ongoing charges figure is the one
 * number that matters to a saver, so it headlines alone as an annual figure and
 * the other details follow as a description
 * @param  array $labels Display labels keyed like $fund, plus per_year
 * @param  array $fund   ongoing_charges, management_fee, redemption_fee,
 *                       inception_date, risk_profile, comparison_index
 * @return array         fees and description sentences
 */
function fund_schema_fields(array $labels, array $fund): array
{
    $details = array_filter([
        $labels['management_fee'] => $fund['management_fee'],
        $labels['redemption_fee'] => $fund['redemption_fee'],
        $labels['inception_date'] => $fund['inception_date'],
        $labels['risk_profile'] => $fund['risk_profile'],
        $labels['comparison_index'] => implode(', ', array_filter((array) $fund['comparison_index'])),
    ]);
    $sentences = array_map(function ($label, $value) {
        return $label . ': ' . $value . '.';
    }, array_keys($details), $details);

    return [
        'fees' => $labels['ongoing_charges'] . ' ' . $fund['ongoing_charges'] . ' ' . $labels['per_year'],
        'description' => implode(' ', $sentences),
    ];
}

/**
 * Renders the InvestmentFund JSON-LD script for a fund page from the same facts
 * the visible fund details table shows
 * @param  array $fund isin, inception_date, management_fee, ongoing_charges,
 *                     redemption_fee, risk_profile, comparison_index and an
 *                     optional name (defaults to the page title)
 * @return string      Script element, or an empty string without an ISIN
 */
function fund_schema_script(array $fund): string
{
    $fields = fund_schema_fields([
        'ongoing_charges' => __('Ongoing charges', TEXT_DOMAIN),
        'per_year' => __('per year', TEXT_DOMAIN),
        'management_fee' => __('Management fee', TEXT_DOMAIN),
        'redemption_fee' => __('Redemption fee and issue fee', TEXT_DOMAIN),
        'inception_date' => __('Date of inception', TEXT_DOMAIN),
        'risk_profile' => __('Risk profile', TEXT_DOMAIN),
        'comparison_index' => __('Comparison index', TEXT_DOMAIN),
    ], $fund);

    $json = fund_schema_json([
        'name' => $fund['name'] ?? get_the_title(),
        'url' => get_permalink(),
        'isin' => $fund['isin'],
        'provider' => 'Tuleva Fondid AS',
        'fees' => $fields['fees'],
        'description' => $fields['description'],
    ]);

    return $json === '' ? '' : '<script type="application/ld+json">' . $json . '</script>';
}

if (function_exists('add_filter')) {
    add_filter('wpseo_schema_organization', function ($organization) {
        return add_organization_details($organization, [
            'legalName' => WPSEO_Options::get('org-legal-name'),
            'foundingDate' => WPSEO_Options::get('org-founding-date'),
            'description' => WPSEO_Options::get('org-description'),
        ]);
    });
}
