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
 * The documents that are the same for every fund (sustainability, adverse impacts,
 * remuneration, the pension funds' NAV procedure) are fields too, on one page of their own,
 * so one update reaches all four fund pages: tuleva_shared_document_url().
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
 * WPML's "copy" custom field preference: a translation takes the original's value.
 */
const TULEVA_WPML_COPY_FROM_ORIGINAL = 1;

/**
 * The template of the one page that holds the documents every fund links. The page is private
 * and never shown (page_fund-shared-documents.php): the documents show on the fund pages.
 */
const TULEVA_SHARED_DOCUMENTS_TEMPLATE = 'page_fund-shared-documents.php';

/**
 * A page whose disclosures are read is published or private (the shared documents page); a
 * draft, a page pending review or one in the trash is not.
 */
const TULEVA_DISCLOSURE_PAGE_STATUSES = ['publish', 'private'];

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
            'instructions' => 'On a fund page, that fund\'s own procedure for determining net asset value. On the shared documents page, the one the three pension funds share.',
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
        'sustainability_risks_file' => [
            'label' => 'Sustainability Risk Policy',
            'type' => 'file',
            'instructions' => 'Jätkusuutlikkusriskide arvesse võtmise põhimõtted, linked from every fund page.',
        ],
        'adverse_impacts_file' => [
            'label' => 'Non-consideration of Adverse Impacts',
            'type' => 'file',
            'instructions' => 'Kestlikkusteguritele avaldatava negatiivse mõju mittearvestamine, linked from every fund page.',
        ],
        'remuneration_policy_file' => [
            'label' => 'Remuneration Policy',
            'type' => 'file',
            'instructions' => 'Tuleva Fondid AS tasustamise põhimõtted, linked from every fund page.',
        ],
    ];
}

/**
 * Which documents each fund page carries, and the shared documents page (not a fund: code
 * that wants only the funds leaves out TULEVA_SHARED_DOCUMENTS_TEMPLATE).
 *
 * Absent from a list means the document does not exist for that fund. Location rules
 * are per page template and every fund has its own, so turning a document off for one
 * fund is one line here and needs no template change.
 */
function tuleva_fund_disclosure_scope(): array
{
    // The three pension funds publish the same set. Their NAV procedure is one shared
    // document for all of them, so it is not a per-page field.
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
        // Not a fund: the documents every fund page links, on one page so one update
        // reaches all four. nav_procedure_* here is the pension funds' shared procedure.
        TULEVA_SHARED_DOCUMENTS_TEMPLATE => [
            'nav_procedure_file' => TULEVA_DISCLOSURE_REQUIRED,
            'nav_procedure_upcoming_file' => TULEVA_DISCLOSURE_OPTIONAL,
            'sustainability_risks_file' => TULEVA_DISCLOSURE_REQUIRED,
            'adverse_impacts_file' => TULEVA_DISCLOSURE_REQUIRED,
            'remuneration_policy_file' => TULEVA_DISCLOSURE_REQUIRED,
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
        if ($requirement === TULEVA_DISCLOSURE_REQUIRED && tuleva_disclosure_stored_value($name, $post_id) === '') {
            $missing[] = $name;
        }
    }

    return $missing;
}

/**
 * What ACF holds for a disclosure, as a string. No fallback, no scope check.
 *
 * Only '' means "nothing stored". A truthiness test would read the string "0" as
 * absent, and a disclosure is a published figure, not a flag.
 */
function tuleva_disclosure_stored_value(string $name, $post_id = null): string
{
    $value = get_field($name, tuleva_disclosure_source_post($post_id));

    // File fields return a URL string; a field left on an older return format hands
    // back the attachment array instead.
    if (is_array($value)) {
        $value = $value['url'] ?? '';
    }

    return is_string($value) ? $value : '';
}

/**
 * The Estonian original of a fund page, which holds the disclosures for every language.
 *
 * The documents are published in Estonian and an English fund page links the same ones,
 * so a value is written once, to the Estonian page, and reaches both. A value left on an
 * English copy by an earlier edit is never read. Without WPML the filter returns the ID
 * it is given.
 */
function tuleva_disclosure_source_post($post_id = null)
{
    $post_id = $post_id ?: (get_the_ID() ?: get_queried_object_id());

    return $post_id ? apply_filters('wpml_object_id', $post_id, 'page', true, 'et') : null;
}

/**
 * The URL to render for a document, or '' when there is nothing to render.
 *
 * A document outside this fund's scope returns '' and never reaches $fallback: an
 * empty TKF100 CO2 figure must not borrow a pension fund's, and a pension fund with
 * no summary of investor rights must not borrow TKF100's.
 *
 * An upcoming document's $fallback is the document after the template's current URL, so it
 * applies only while that URL is the one shown, the current field being empty. Once an editor
 * has set the current field only the upcoming field can replace it: the template's date would
 * otherwise take a newer document off the page whenever that one's filename has no date.
 */
function tuleva_fund_disclosure_value(string $name, string $fallback = '', ?string $template = null): string
{
    if (!tuleva_fund_disclosure_applies($name, $template)) {
        return '';
    }

    $value = tuleva_disclosure_stored_value($name);

    if ($value !== '') {
        return $value;
    }

    $current = array_search($name, TULEVA_UPCOMING_DISCLOSURES, true);

    return $current !== false && tuleva_disclosure_stored_value($current) !== '' ? '' : $fallback;
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
    $date = tuleva_document_effective_parts($url);

    return $date === null ? $fallback : "{$date[0]}.{$date[1]}.{$date[2]}";
}

/**
 * The same date as 'YYYY-MM-DD', which compares as a string, or '' when the filename
 * carries none.
 */
function tuleva_document_effective_day(string $url): string
{
    $date = tuleva_document_effective_parts($url);

    return $date === null ? '' : "{$date[2]}-{$date[1]}-{$date[0]}";
}

/**
 * @return array{0: string, 1: string, 2: string}|null day, month and year as the filename spells them
 */
function tuleva_document_effective_parts(string $url): ?array
{
    if (!preg_match('/alates[-_. ](\d{2})\.(\d{2})\.(\d{4})/i', $url, $matches)) {
        return null;
    }

    [, $day, $month, $year] = array_map('intval', $matches);

    // A filename is a hand-typed string driving a statement about when a document takes
    // effect, so refuse anything that cannot be one. The pension funds' NAV procedure is
    // published as "...kehtib-alates-02.03.3026.pdf" — a typo for 2026 — which is
    // harmless while the date is unused and is not once it is rendered.
    if (!checkdate($month, $day, $year) || abs($year - (int) date('Y')) > 10) {
        return null;
    }

    return [$matches[1], $matches[2], $matches[3]];
}

/**
 * Today in Tallinn, where a document's effective date is a calendar day. A filter so the
 * tests can hold the day still.
 */
function tuleva_today(): string
{
    return apply_filters(
        'tuleva_today',
        (new DateTimeImmutable('now', new DateTimeZone('Europe/Tallinn')))->format('Y-m-d')
    );
}

/**
 * Each document that has an upcoming version, by its current field.
 */
const TULEVA_UPCOMING_DISCLOSURES = [
    'prospectus_file' => 'prospectus_upcoming_file',
    'terms_file' => 'terms_upcoming_file',
    'nav_procedure_file' => 'nav_procedure_upcoming_file',
];

/**
 * A document's current and upcoming URL, once the upcoming one's date is taken into account.
 *
 * From its effective date an upcoming document is the current one, so the page switches on
 * that day with nobody acting: the switch was approved when the upcoming document was set.
 * An upcoming document no newer than the current one is a leftover and is dropped, not
 * promoted: an upcoming field left behind once the current field moved past it. The two are of
 * one standing, two fields or two template URLs; a filled field beats a template URL whatever the
 * dates (tuleva_fund_document_versions()).
 *
 * @return array{0: string, 1: string} the current URL and the upcoming URL
 */
function tuleva_document_versions(string $current, string $upcoming, ?string $today = null): array
{
    $takes_effect = tuleva_document_effective_day($upcoming);

    if ($takes_effect === '' || $takes_effect > ($today ?: tuleva_today())) {
        return [$current, $upcoming];
    }

    $current_since = tuleva_document_effective_day($current);

    return $current_since !== '' && $current_since >= $takes_effect ? [$current, ''] : [$upcoming, ''];
}

/**
 * tuleva_document_versions() on a fund page, whose URLs may be the template's: $current and
 * $upcoming are what tuleva_fund_disclosure_value() gave for $name and its upcoming field.
 *
 * A filled field beats a template URL whatever the dates. While the current field is empty the
 * page shows the template's URL, and an upcoming field takes over from it on its date even if the
 * URL is newer. The hourly job, which sees only the fields, then makes the same switch, so the page
 * does not change when it runs. A template's upcoming URL meets only the template's current URL
 * (tuleva_fund_disclosure_value()), and the two are compared by date.
 *
 * @return array{0: string, 1: string} the current URL and the upcoming URL
 */
function tuleva_fund_document_versions(string $name, string $current, string $upcoming, ?string $today = null): array
{
    if (tuleva_disclosure_stored_value($name) === ''
        && tuleva_disclosure_stored_value(TULEVA_UPCOMING_DISCLOSURES[$name]) !== '') {
        [$now, $next] = tuleva_document_versions('', $upcoming, $today);

        return [$now !== '' ? $now : $current, $next];
    }

    return tuleva_document_versions($current, $upcoming, $today);
}

/**
 * The pages on a template whose disclosures are read: the Estonian originals, themselves
 * published or private and on that template (a translation can be published while its original
 * is not, or sit on another template).
 *
 * @return list<int>
 */
function tuleva_fund_pages_on(string $template): array
{
    $pages = get_posts([
        'post_type' => 'page',
        'post_status' => TULEVA_DISCLOSURE_PAGE_STATUSES,
        'meta_key' => '_wp_page_template',
        'meta_value' => $template,
        'fields' => 'ids',
        'numberposts' => -1,
        'suppress_filters' => true,
    ]);

    $originals = array_unique(array_map(
        fn($page) => (int) tuleva_disclosure_source_post((int) $page),
        $pages
    ));

    return array_values(array_filter(
        $originals,
        fn($page) => in_array(get_post_status($page), TULEVA_DISCLOSURE_PAGE_STATUSES, true)
            && get_page_template_slug($page) === $template
    ));
}

/**
 * The URL of a document every fund links, from the shared documents page, or $fallback while
 * its field is empty or no page is on that template yet.
 */
function tuleva_shared_document_url(string $name, string $fallback = ''): string
{
    $page = tuleva_shared_documents_page();
    $value = $page === null ? '' : tuleva_disclosure_stored_value($name, $page);

    return $value !== '' ? $value : $fallback;
}

/**
 * The page on the shared documents template, looked up once a request: a fund page asks for
 * five documents. One page is meant to be on it; if there are more, the oldest is read.
 */
function tuleva_shared_documents_page(): ?int
{
    $found = &tuleva_shared_documents_page_found();

    if (!array_key_exists('page', $found)) {
        $pages = tuleva_fund_pages_on(TULEVA_SHARED_DOCUMENTS_TEMPLATE);
        $found['page'] = $pages === [] ? null : min($pages);
    }

    return $found['page'];
}

/**
 * @return array{page?: ?int} what tuleva_shared_documents_page() found, for the rest of the request
 */
function &tuleva_shared_documents_page_found(): array
{
    static $found = [];

    return $found;
}

/**
 * What keeps the fund pages from reading the shared documents page as meant, for wp-admin, or ''.
 * Either way they show the URLs in the theme and nothing on them says so: of several pages on the
 * template only the oldest is read, and a page left in draft (what a page created over REST is
 * unless it says otherwise) is not read at all. No page yet is not a problem: the URLs are meant.
 */
function tuleva_shared_documents_page_problem(): string
{
    $pages = array_values(array_unique(array_map(
        fn($page) => (int) tuleva_disclosure_source_post((int) $page),
        get_posts([
            'post_type' => 'page',
            'post_status' => 'any',
            'meta_key' => '_wp_page_template',
            'meta_value' => TULEVA_SHARED_DOCUMENTS_TEMPLATE,
            'fields' => 'ids',
            'numberposts' => -1,
            'suppress_filters' => true,
        ])
    )));
    $read = tuleva_shared_documents_page();

    if (count($pages) > 1) {
        return sprintf(
            $read === null
                ? '%d pages are on the Fund shared documents template (%s) and none is private or published, so the fund pages show the URLs in the theme. Keep one page on the template and make it Private.'
                : '%d pages are on the Fund shared documents template (%s). The fund pages read only page %d; the others change nothing on them. Keep one page on the template.',
            count($pages),
            implode(', ', $pages),
            $read
        );
    }

    if ($pages !== [] && $read === null) {
        return sprintf(
            'The Fund shared documents page (%d) has the status %s, so the fund pages do not read it and show the URLs in the theme. Make it Private.',
            $pages[0],
            get_post_status($pages[0])
        );
    }

    return '';
}

/**
 * The problem, on the edit screen of a page that reads the shared documents page (a fund page)
 * or of that page itself; '' on every other screen.
 */
function tuleva_shared_documents_page_notice(): string
{
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;

    if (!$screen || $screen->base !== 'post' || $screen->post_type !== 'page') {
        return '';
    }

    $page = get_the_ID();

    return $page && array_key_exists((string) get_page_template_slug($page), tuleva_fund_disclosure_scope())
        ? tuleva_shared_documents_page_problem()
        : '';
}

// The classic editor shows admin notices; the block editor hides them and takes its own.
add_action('admin_notices', function (): void {
    $notice = tuleva_shared_documents_page_notice();

    if ($notice !== '') {
        echo '<div class="notice notice-warning"><p>' . esc_html($notice) . '</p></div>';
    }
});
add_action('enqueue_block_editor_assets', function (): void {
    $notice = tuleva_shared_documents_page_notice();

    if ($notice !== '') {
        wp_add_inline_script(
            'wp-notices',
            sprintf('wp.data.dispatch("core/notices").createNotice("warning", %s, {isDismissible: false});', wp_json_encode($notice))
        );
    }
});

/**
 * Writes each switch tuleva_document_versions() makes on a page into its fields: the current
 * field gets the upcoming document and the upcoming field is emptied, and an upcoming field
 * whose document the page drops as a leftover is emptied too. The REST `acf` values that the
 * app and agent-gateway read then agree with what the page shows, and the next upcoming
 * document has an empty field to go into. The page itself switches at midnight whether or
 * not this has run.
 *
 * A page whose current field is empty is no exception: it shows the template's URL, which this
 * job cannot see, but a due upcoming field beats that whatever its date
 * (tuleva_fund_document_versions()), so promoting it there changes nothing on the page either.
 * The upcoming field is emptied only once the current field reads back as the promoted document,
 * so a write that did not land is tried again the next hour, and only while it still holds the
 * document the job read: one saved there meanwhile is the next upcoming document and stays.
 *
 * @return list<array{post: int, field: string}> the documents promoted
 */
function tuleva_promote_due_documents(?string $today = null): array
{
    $promoted = [];

    if (!function_exists('update_field')) {
        return $promoted;
    }

    foreach (tuleva_fund_disclosure_scope() as $template => $scope) {
        foreach (tuleva_fund_pages_on($template) as $page) {
            foreach (TULEVA_UPCOMING_DISCLOSURES as $current => $upcoming) {
                if (!isset($scope[$current], $scope[$upcoming])) {
                    continue;
                }

                $upcoming_id = (int) tuleva_disclosure_stored_id_now($upcoming, $page);
                $upcoming_url = $upcoming_id > 0 ? (string) wp_get_attachment_url($upcoming_id) : '';

                if ($upcoming_url === '') {
                    continue;
                }

                $current_id = (int) get_field($current, $page, false);
                $current_url = $current_id > 0 ? (string) wp_get_attachment_url($current_id) : '';

                [$now, $next] = tuleva_document_versions($current_url, $upcoming_url, $today);

                if ($next !== '') {
                    continue;
                }

                if ($now === $upcoming_url) {
                    update_field(tuleva_fund_disclosure_field($current, $template)['key'], $upcoming_id, $page);

                    if ((int) get_field($current, $page, false) !== $upcoming_id) {
                        continue;
                    }

                    $promoted[] = ['post' => $page, 'field' => $current];
                }

                if ((int) tuleva_disclosure_stored_id_now($upcoming, $page) !== $upcoming_id) {
                    continue;
                }

                update_field(tuleva_fund_disclosure_field($upcoming, $template)['key'], '', $page);
            }
        }
    }

    return $promoted;
}

/**
 * A field's stored value as the database holds it now. WordPress caches a page's meta, and ACF
 * each value, on the first read in a request; another request's save since then would not show.
 */
function tuleva_disclosure_stored_id_now(string $name, int $page)
{
    wp_cache_delete($page, 'post_meta');

    if (function_exists('acf_flush_value_cache')) {
        acf_flush_value_cache($page, $name);
    }

    return get_field($name, $page, false);
}

// Hourly, so the fields follow the page within an hour of midnight.
add_action('tuleva_promote_due_documents', 'tuleva_promote_due_documents', 10, 0);
add_action('init', function (): void {
    if (!wp_next_scheduled('tuleva_promote_due_documents')) {
        wp_schedule_event(time(), 'hourly', 'tuleva_promote_due_documents');
    }
});

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
        'wpml_cf_preferences' => TULEVA_WPML_COPY_FROM_ORIGINAL,
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
        'title' => 'Fund Documents & Disclosures (set on the Estonian page, English shows the same)',
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
