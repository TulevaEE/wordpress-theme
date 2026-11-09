<?php
/**
 * What a fund page is missing: a required document or figure whose field is empty, and a document
 * whose filename carries no effective date the page can read. Shown on the page's edit screen and
 * in its REST answer as `tuleva_disclosure_gaps`, which onboarding-service checks every morning.
 *
 * A fund page shows the documents every fund links from the shared documents page, so its gaps
 * include that page's, marked as the shared page's.
 */

const TULEVA_DISCLOSURE_GAP_MISSING = 'missing';
const TULEVA_DISCLOSURE_GAP_NO_EFFECTIVE_DATE = 'no_effective_date';
const TULEVA_DISCLOSURE_GAP_ON_FUND_PAGE = 'fund';
const TULEVA_DISCLOSURE_GAP_ON_SHARED_PAGE = 'shared';

/**
 * The gaps of a page on a fund template or the shared documents template, the fund page's own
 * first; [] on any other page.
 *
 * @return list<array{field: string, problem: string, page: string}>
 */
function tuleva_fund_disclosure_gaps(string $template, $post_id = null): array
{
    $scope = tuleva_fund_disclosure_scope();

    if (!isset($scope[$template])) {
        return [];
    }

    if ($template === TULEVA_SHARED_DOCUMENTS_TEMPLATE) {
        return tuleva_disclosure_gaps_in(
            $scope[$template],
            fn(string $name) => tuleva_disclosure_stored_value($name, $post_id),
            TULEVA_DISCLOSURE_GAP_ON_SHARED_PAGE
        );
    }

    // The shared documents a fund page shows: the shared page's, less the ones the fund holds
    // itself (TKF100's own NAV procedure).
    $shown_from_shared_page = array_diff_key($scope[TULEVA_SHARED_DOCUMENTS_TEMPLATE], $scope[$template]);
    $shared_page = tuleva_shared_documents_page();

    return [
        ...tuleva_disclosure_gaps_in(
            $scope[$template],
            fn(string $name) => tuleva_disclosure_stored_value($name, $post_id),
            TULEVA_DISCLOSURE_GAP_ON_FUND_PAGE
        ),
        ...tuleva_disclosure_gaps_in(
            $shown_from_shared_page,
            fn(string $name) => $shared_page === null ? '' : tuleva_disclosure_stored_value($name, $shared_page),
            TULEVA_DISCLOSURE_GAP_ON_SHARED_PAGE
        ),
    ];
}

/**
 * @param array<string, string> $scope field name => requirement
 * @param callable(string): string $stored_value
 * @return list<array{field: string, problem: string, page: string}>
 */
function tuleva_disclosure_gaps_in(array $scope, callable $stored_value, string $page): array
{
    // The documents that switch on the date in their filename, current and upcoming.
    $dated = [...array_keys(TULEVA_UPCOMING_DISCLOSURES), ...array_values(TULEVA_UPCOMING_DISCLOSURES)];
    $gaps = [];

    foreach ($scope as $name => $requirement) {
        $value = $stored_value($name);

        if ($value === '' && $requirement === TULEVA_DISCLOSURE_REQUIRED) {
            $gaps[] = ['field' => $name, 'problem' => TULEVA_DISCLOSURE_GAP_MISSING, 'page' => $page];
        } elseif ($value !== '' && in_array($name, $dated, true) && tuleva_document_effective_day($value) === '') {
            $gaps[] = ['field' => $name, 'problem' => TULEVA_DISCLOSURE_GAP_NO_EFFECTIVE_DATE, 'page' => $page];
        }
    }

    return $gaps;
}

/**
 * The gaps as one notice for an edit screen, or '' when there are none.
 *
 * @param list<array{field: string, problem: string, page: string}> $gaps
 */
function tuleva_describe_disclosure_gaps(array $gaps): string
{
    $fields = fn(string $problem, ?string $page = null) => array_column(array_filter(
        $gaps,
        fn(array $gap) => $gap['problem'] === $problem && ($page === null || $gap['page'] === $page)
    ), 'field');
    $sentences = array_filter([
        'Required on this page and empty' => $fields(TULEVA_DISCLOSURE_GAP_MISSING, TULEVA_DISCLOSURE_GAP_ON_FUND_PAGE),
        'Required on the shared documents page and empty' => $fields(TULEVA_DISCLOSURE_GAP_MISSING, TULEVA_DISCLOSURE_GAP_ON_SHARED_PAGE),
        'No effective date in the filename' => $fields(TULEVA_DISCLOSURE_GAP_NO_EFFECTIVE_DATE),
    ]);

    if ($sentences === []) {
        return '';
    }

    $described = array_map(
        fn(string $what, array $names) => $what . ': ' . implode(', ', $names) . '.',
        array_keys($sentences),
        $sentences
    );

    return implode(' ', $described) . ' Set them through agent-gateway fund_pages.';
}

/**
 * The gaps of the page being edited, on its edit screen; '' on every other screen and on a page
 * that is not a fund page or the shared documents page.
 */
function tuleva_disclosure_gaps_notice(): string
{
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'page') {
        return '';
    }

    $page = get_the_ID();

    return $page ? tuleva_describe_disclosure_gaps(tuleva_fund_disclosure_gaps((string) get_page_template_slug($page), $page)) : '';
}

/**
 * admin_notices, for the classic editor. The block editor hides admin notices and gets its own
 * (tuleva_disclosure_gaps_block_editor_notice()).
 */
function tuleva_disclosure_gaps_admin_notice(): void
{
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if ($screen && method_exists($screen, 'is_block_editor') && $screen->is_block_editor()) {
        return;
    }

    $notice = tuleva_disclosure_gaps_notice();

    if ($notice !== '') {
        echo '<div class="notice notice-error"><p>' . esc_html($notice) . '</p></div>';
    }
}

/**
 * enqueue_block_editor_assets.
 */
function tuleva_disclosure_gaps_block_editor_notice(): void
{
    $notice = tuleva_disclosure_gaps_notice();

    if ($notice !== '') {
        wp_add_inline_script(
            'wp-notices',
            sprintf(
                'wp.data.dispatch("core/notices").createNotice("error", %s, {id: "tuleva-disclosure-gaps", isDismissible: false});',
                wp_json_encode($notice)
            )
        );
    }
}

/**
 * The REST answer's `tuleva_disclosure_gaps`: the page's gaps, an English page's those of its
 * Estonian original, and null on a page that is not a fund page or the shared documents page.
 *
 * @param array{id: int} $page
 * @return list<array{field: string, problem: string, page: string}>|null
 */
function tuleva_disclosure_gaps_for_rest(array $page): ?array
{
    $template = (string) get_page_template_slug($page['id']);

    return isset(tuleva_fund_disclosure_scope()[$template]) ? tuleva_fund_disclosure_gaps($template, $page['id']) : null;
}

function tuleva_register_disclosure_gaps_rest_field(): void
{
    register_rest_field('page', 'tuleva_disclosure_gaps', [
        'get_callback' => 'tuleva_disclosure_gaps_for_rest',
        'schema' => [
            'description' => 'Required documents this fund page is missing, and documents whose filename carries no effective date.',
            'type' => ['array', 'null'],
            'context' => ['view', 'edit'],
            'readonly' => true,
            'items' => [
                'type' => 'object',
                'properties' => [
                    'field' => ['type' => 'string'],
                    'problem' => ['type' => 'string', 'enum' => [TULEVA_DISCLOSURE_GAP_MISSING, TULEVA_DISCLOSURE_GAP_NO_EFFECTIVE_DATE]],
                    'page' => ['type' => 'string', 'enum' => [TULEVA_DISCLOSURE_GAP_ON_FUND_PAGE, TULEVA_DISCLOSURE_GAP_ON_SHARED_PAGE]],
                ],
            ],
        ],
    ]);
}

add_action('admin_notices', 'tuleva_disclosure_gaps_admin_notice');
add_action('enqueue_block_editor_assets', 'tuleva_disclosure_gaps_block_editor_notice');
add_action('rest_api_init', 'tuleva_register_disclosure_gaps_rest_field');
