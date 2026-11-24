<?php
/*
 * A fund's ongoing charges or management fee in text typed in WP Admin: page text, blog
 * posts and Yoast descriptions. Each prints the figure exactly as the fund pages do
 * (helpers/fund-figures.php): the fund list's figure in the page's language, or the last one
 * it returned while it cannot be read.
 *
 *   [tuleva_ongoing_charges fund="TKF100" fallback="0,28%"]
 *   [tuleva_ongoing_charges fund="all" fallback="0,28%"]  all four funds: one value, or the lowest–highest
 *   [tuleva_management_fee fund="TUK75" fallback="0,16%"]
 *
 *   %%tuleva_ongoing_charges_tkf100%% (and _tuk75, _tuk00, _tuv100) in a Yoast description
 *
 * With no figure to print, or a fund code the theme does not know, a shortcode prints its
 * fallback, so the sentence around it never has a gap. A logged-in editor also sees a warning
 * beside it when the fund code is unknown or the fallback is missing; readers never do.
 * Shortcodes run in a post's content, headings in it included, but not in its title or in
 * menus.
 */

const TULEVA_FUND_FIGURE_SHORTCODES = [
    'tuleva_ongoing_charges' => 'ongoing_charges',
    'tuleva_management_fee' => 'management_fee',
];

/*
 * What a fund figure shortcode prints: the figure, else the fallback, with a warning for an
 * editor when the fund code is unknown or there is no fallback to fall back on.
 */
function tuleva_fund_figure_shortcode(string $tag, $atts, bool $editor): string
{
    $atts = is_array($atts) ? $atts : [];
    $code = strtoupper(trim((string) ($atts['fund'] ?? '')));
    $fallback = trim((string) ($atts['fallback'] ?? ''));
    $figure = tuleva_fund_figure_for_code(TULEVA_FUND_FIGURE_SHORTCODES[$tag], $code);

    $warnings = [];
    if ($figure === false) {
        $warnings[] = sprintf('fund "%s" is not one of %s', $code, implode(', ', tuleva_fund_figure_codes($tag)));
    }
    if ($fallback === '') {
        $warnings[] = 'it has no fallback, so readers see nothing when there is no figure';
    }

    $printed = is_string($figure) ? $figure : $fallback;
    if (!$editor || $warnings === []) {
        return esc_html($printed);
    }

    return esc_html($printed) . sprintf(
        ' <mark class="tuleva-shortcode-warning">[%s: %s. Only logged-in editors see this.]</mark>',
        esc_html($tag),
        esc_html(implode('; ', $warnings))
    );
}

/*
 * A fund's figure as the fund pages print it, null while there is none to print, or false for
 * a fund code the theme does not know. "ALL" is every Tuleva fund's ongoing charges.
 */
function tuleva_fund_figure_for_code(string $figure, string $code): string|false|null
{
    if ($figure === 'ongoing_charges' && $code === 'ALL') {
        $range = tuleva_all_funds_ongoing_charges();

        return $range === null ? null : implode('–', $range);
    }

    $isin = TULEVA_FUND_ISINS[$code] ?? null;
    if ($isin === null) {
        return false;
    }

    return tuleva_fund_page_figures($isin)[$figure];
}

/**
 * @return list<string> the fund codes a shortcode takes
 */
function tuleva_fund_figure_codes(string $tag): array
{
    $codes = array_keys(TULEVA_FUND_ISINS);

    return TULEVA_FUND_FIGURE_SHORTCODES[$tag] === 'ongoing_charges' ? [...$codes, 'all'] : $codes;
}

function tuleva_ongoing_charges_shortcode($atts): string
{
    return tuleva_fund_figure_shortcode('tuleva_ongoing_charges', $atts, current_user_can('edit_posts'));
}
add_shortcode('tuleva_ongoing_charges', 'tuleva_ongoing_charges_shortcode');

function tuleva_management_fee_shortcode($atts): string
{
    return tuleva_fund_figure_shortcode('tuleva_management_fee', $atts, current_user_can('edit_posts'));
}
add_shortcode('tuleva_management_fee', 'tuleva_management_fee_shortcode');

/*
 * A post's automatic excerpt on the blog listing strips shortcodes before it renders the text
 * (wp_trim_excerpt), which would leave a gap where the figure was. While the excerpt is being
 * made, these two are kept, and the excerpt's own the_content pass prints them. Elsewhere they
 * are stripped as WordPress strips any shortcode, so their raw text never shows.
 */
function tuleva_keep_fund_figure_shortcodes_in_excerpts($tags)
{
    if (!is_array($tags) || !doing_filter('get_the_excerpt')) {
        return $tags;
    }

    return array_values(array_diff($tags, array_keys(TULEVA_FUND_FIGURE_SHORTCODES)));
}
add_filter('strip_shortcodes_tagnames', 'tuleva_keep_fund_figure_shortcodes_in_excerpts');

/*
 * One Yoast SEO variable per fund for its ongoing charges, %%tuleva_ongoing_charges_tkf100%%
 * and so on. Yoast has no fallback for a variable, so with no figure ever read it is empty.
 * The hook fires only while Yoast is active.
 */
function tuleva_register_fund_figure_yoast_variables(): void
{
    foreach (TULEVA_FUND_ISINS as $code => $isin) {
        wpseo_register_var_replacement(
            '%%tuleva_ongoing_charges_' . strtolower($code) . '%%',
            fn() => tuleva_fund_page_figures($isin)['ongoing_charges'] ?? '',
            'advanced',
            $code . "'s ongoing charges as its key information document prints them"
        );
    }
}
add_action('wpseo_register_extra_replacements', 'tuleva_register_fund_figure_yoast_variables');
