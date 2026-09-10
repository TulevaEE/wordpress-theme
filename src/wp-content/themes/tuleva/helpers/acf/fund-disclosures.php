<?php
/**
 * What each fund publishes on its page: one field vocabulary, one declaration of which
 * fund has what.
 *
 * Covers the documents (prospectus, terms, key investor information, model portfolio,
 * investment report) and the figures disclosed beside them (CO2 intensity). A
 * disclosure's field name is identical on every fund page that carries it, so nothing
 * downstream branches per fund — a publishing script or onboarding-service resolves
 * fund → page slug and disclosure → field name, and that is the whole mapping.
 *
 * What differs per fund is whether the field exists at all. TKF100 is a UCITS fund and
 * publishes a summary of investor rights and its own NAV procedure; the pension funds
 * publish neither, and TKF100 has no CO2 intensity figure because none is calculated
 * for it. A disclosure absent from a fund's scope has no field in wp-admin, no key in
 * that page's REST response, and renders nothing — which is a different thing from a
 * disclosure that applies but has not been supplied yet.
 *
 * See "TODO — Fund page document publishing" in the tuleva repo,
 * work/investeerimistegevus/docs/.
 */

/**
 * Empty is a gap worth reporting: the fund is expected to publish this.
 */
const TULEVA_DISCLOSURE_REQUIRED = 'required';

/**
 * Empty is normal: an upcoming document exists only between approval and its
 * effective date.
 */
const TULEVA_DISCLOSURE_OPTIONAL = 'optional';

/**
 * Every disclosure a fund page can carry, defined once.
 *
 * 'keys' pins the ACF field key on pages where the field already exists. ACF stores a
 * value under the field *name* and a reference to the key alongside it, so generating
 * a fresh key for an existing field leaves the value in place but the reference
 * dangling. TKF100's fields predate this catalogue and keep the keys they were
 * registered with.
 */
function tuleva_fund_disclosure_catalogue(): array
{
    return [
        'prospectus_file' => [
            'label' => 'Prospectus',
            'type' => 'file',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_prospectus'],
        ],
        'terms_file' => [
            'label' => 'Terms and Conditions',
            'type' => 'file',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_terms'],
        ],
        'prospectus_upcoming_file' => [
            'label' => 'Prospectus (Upcoming)',
            'type' => 'file',
            'instructions' => 'Approved but not yet effective. The effective date is read from the filename, so keep the "kehtib alates DD.MM.YYYY" part.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_prospectus_upcoming'],
        ],
        'terms_upcoming_file' => [
            'label' => 'Terms and Conditions (Upcoming)',
            'type' => 'file',
            'instructions' => 'Approved but not yet effective. The effective date is read from the filename.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_terms_upcoming'],
        ],
        'model_portfolio_file' => [
            'label' => 'Model Portfolio',
            'type' => 'file',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_model_portfolio'],
        ],
        'key_investor_info_file' => [
            'label' => 'Key Investor Information',
            'type' => 'file',
            'instructions' => 'Põhiteave for the pension funds, PRIIPs KID for TKF100.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_key_investor_info'],
        ],
        'nav_procedure_file' => [
            'label' => 'NAV Procedure',
            'type' => 'file',
            'instructions' => 'This fund\'s own procedure for determining net asset value. The pension funds share one document set on the options page and have no field here.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_nav_procedure'],
        ],
        'nav_procedure_upcoming_file' => [
            'label' => 'NAV Procedure (Upcoming)',
            'type' => 'file',
            'instructions' => 'Approved but not yet effective. The effective date is read from the filename.',
        ],
        'investor_rights_file' => [
            'label' => 'Summary of Investor Rights',
            'type' => 'file',
            'instructions' => 'UCITS funds only.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_investor_rights'],
        ],
        'investment_report_file' => [
            'label' => 'Investment Report',
            'type' => 'file',
            'instructions' => 'The latest monthly report. onboarding-service sets this over the REST API; the reporting period is read from the filename.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_investment_report'],
        ],
        'previous_reports_url' => [
            'label' => 'Previous Reports URL',
            'type' => 'url',
            'instructions' => 'Archive of earlier investment reports, usually on pensionikeskus.ee.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_previous_reports_url'],
        ],
        'fund_co2_intensity' => [
            'label' => 'CO2 Intensity',
            // Text, not number: the figure is rendered verbatim and trailing zeros are
            // significant — "133.80" must not collapse to "133.8".
            'type' => 'text',
            'instructions' => 'Weighted average carbon intensity, number only (e.g. "83.68"). Quarterly.',
            'keys' => ['page_fund-savings.php' => 'field_fund_savings_co2_intensity'],
        ],
    ];
}

/**
 * Which documents each fund page carries.
 *
 * Absent from a list means the document does not exist for that fund. Location rules
 * are per page template and every fund has its own, so turning a document off for one
 * fund is one line here and needs no template change.
 */
function tuleva_fund_disclosure_scope(): array
{
    // The three pension funds publish the same set. Their NAV procedure is one shared
    // document for all of them, so it lives on the options page rather than per page.
    $pension_fund = [
        'prospectus_file' => TULEVA_DISCLOSURE_REQUIRED,
        'terms_file' => TULEVA_DISCLOSURE_REQUIRED,
        'prospectus_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
        'terms_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
        'model_portfolio_file' => TULEVA_DISCLOSURE_REQUIRED,
        'key_investor_info_file' => TULEVA_DISCLOSURE_REQUIRED,
        'investment_report_file' => TULEVA_DISCLOSURE_REQUIRED,
        'previous_reports_url' => TULEVA_DISCLOSURE_OPTIONAL,
        'fund_co2_intensity' => TULEVA_DISCLOSURE_REQUIRED,
    ];

    return [
        'page_fund-stocks.php' => $pension_fund,
        'page_fund-bonds.php' => $pension_fund,
        'page_fund-third.php' => $pension_fund,
        'page_fund-savings.php' => [
            'prospectus_file' => TULEVA_DISCLOSURE_REQUIRED,
            'terms_file' => TULEVA_DISCLOSURE_REQUIRED,
            'prospectus_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
            'terms_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
            'model_portfolio_file' => TULEVA_DISCLOSURE_REQUIRED,
            'key_investor_info_file' => TULEVA_DISCLOSURE_REQUIRED,
            'nav_procedure_file' => TULEVA_DISCLOSURE_REQUIRED,
            'nav_procedure_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
            'investor_rights_file' => TULEVA_DISCLOSURE_REQUIRED,
            'investment_report_file' => TULEVA_DISCLOSURE_REQUIRED,
            'previous_reports_url' => TULEVA_DISCLOSURE_OPTIONAL,
            // fund_co2_intensity is deliberately absent: no CO2 intensity is calculated
            // for TKF100. The savings template renders the block the moment this line
            // exists, so starting to publish one is an edit here and nothing else.
        ],
    ];
}

/**
 * 'required', 'optional', or null when the document does not apply to that fund.
 */
function tuleva_fund_disclosure_requirement(string $name, string $template): ?string
{
    return tuleva_fund_disclosure_scope()[$template][$name] ?? null;
}

function tuleva_fund_disclosure_applies(string $name, ?string $template = null): bool
{
    $template = $template ?? tuleva_current_fund_template();

    return $template !== '' && tuleva_fund_disclosure_requirement($name, $template) !== null;
}

/**
 * Documents a fund is expected to publish but has not got a value for. Optional
 * documents and documents outside the fund's scope never appear here.
 */
function tuleva_fund_disclosures_missing(?string $template = null, $post_id = null): array
{
    $template = $template ?? tuleva_current_fund_template();
    $missing = [];

    foreach (tuleva_fund_disclosure_scope()[$template] ?? [] as $name => $requirement) {
        if ($requirement === TULEVA_DISCLOSURE_REQUIRED && !get_field($name, $post_id)) {
            $missing[] = $name;
        }
    }

    return $missing;
}

/**
 * The URL to render for a document, or '' when there is nothing to render.
 *
 * A document outside this fund's scope returns '' and never reaches $fallback: an
 * empty TKF100 CO2 figure must not borrow a pension fund's, and a pension fund with
 * no summary of investor rights must not borrow TKF100's.
 */
function tuleva_fund_disclosure_value(string $name, string $fallback = '', ?string $template = null): string
{
    if (!tuleva_fund_disclosure_applies($name, $template)) {
        return '';
    }

    $value = get_field($name);

    // File fields return a URL string; a field left on an older return format hands
    // back the attachment array instead.
    if (is_array($value)) {
        $value = $value['url'] ?? '';
    }

    return is_string($value) && $value !== '' ? $value : $fallback;
}

/**
 * The date an upcoming document takes effect, read from its filename.
 *
 * Filenames carry it in the form the documents themselves use — "kehtib alates",
 * "kehtivad alates", or bare "alates" — and the publishing script controls filenames,
 * whereas a media library title is hand-typed and drifts.
 */
function tuleva_document_effective_date(string $url, string $fallback = ''): string
{
    if (!preg_match('/alates[-_. ](\d{2})\.(\d{2})\.(\d{4})/i', $url, $matches)) {
        return $fallback;
    }

    [, $day, $month, $year] = array_map('intval', $matches);

    // A filename is a hand-typed string driving a statement about when a document takes
    // effect, so refuse anything that cannot be one. The pension funds' NAV procedure is
    // published as "...kehtib-alates-02.03.3026.pdf" — a typo for 2026 — which is
    // harmless while the date is unused and is not once it is rendered.
    if (!checkdate($month, $day, $year) || abs($year - (int) date('Y')) > 10) {
        return $fallback;
    }

    return $matches[1] . '.' . $matches[2] . '.' . $matches[3];
}

/**
 * '' when the current page is not a fund page.
 */
function tuleva_current_fund_template(): string
{
    if (!function_exists('get_page_template_slug')) {
        return '';
    }

    $template = get_page_template_slug();

    return is_string($template) ? $template : '';
}

/**
 * 'page_fund-stocks.php' → 'stocks'. Used to namespace generated ACF keys.
 */
function tuleva_fund_template_slug(string $template): string
{
    $slug = preg_replace('/^page_fund-|^page_|\.php$/', '', $template);

    return strtolower(preg_replace('/[^A-Za-z0-9]+/', '_', $slug));
}

function tuleva_fund_disclosure_field(string $name, string $template): array
{
    $document = tuleva_fund_disclosure_catalogue()[$name];
    $requirement = tuleva_fund_disclosure_requirement($name, $template);
    $type = $document['type'] ?? 'file';

    $field = [
        'key' => $document['keys'][$template]
            ?? 'field_fund_disclosure_' . tuleva_fund_template_slug($template) . '_' . $name,
        'label' => $document['label'],
        'name' => $name,
        'type' => $type,
        'instructions' => $document['instructions'] ?? '',
        // Never ACF-required: an empty required document must leave the page rendering
        // rather than block an unrelated edit to the page. Gaps are reported instead.
        'required' => 0,
    ];

    if ($type === 'file') {
        $field['return_format'] = 'url';
        $field['library'] = 'all';
        $field['mime_types'] = 'pdf';
    }

    if ($requirement === TULEVA_DISCLOSURE_OPTIONAL && $field['instructions'] === '') {
        $field['instructions'] = 'Optional.';
    }

    return $field;
}

function tuleva_fund_disclosure_field_group(string $template): array
{
    $documents = tuleva_fund_disclosure_scope()[$template] ?? [];

    return [
        'key' => 'group_fund_disclosures_' . tuleva_fund_template_slug($template),
        'title' => 'Fund Documents & Disclosures',
        'fields' => array_map(
            fn($name) => tuleva_fund_disclosure_field($name, $template),
            array_keys($documents)
        ),
        'location' => [
            [
                [
                    'param' => 'page_template',
                    'operator' => '==',
                    'value' => $template,
                ],
            ],
        ],
        'menu_order' => 2,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'active' => 1,
        // ACF honours the "acf" key in POST /wp-json/wp/v2/pages/{id} only when the group
        // declares this. Without it ACF drops the key, the request still returns 200, and
        // the write silently does nothing — which is why no ACF write to a fund page has
        // ever landed.
        'show_in_rest' => 1,
    ];
}

if (function_exists('acf_add_local_field_group')) {
    foreach (array_keys(tuleva_fund_disclosure_scope()) as $tuleva_template) {
        acf_add_local_field_group(tuleva_fund_disclosure_field_group($tuleva_template));
    }

    unset($tuleva_template);
}
