<?php
/**
 * Search engine and page speed fixes that are not page content: the font host's
 * connection hint, and serving small images where a template holds only an
 * upload's full-size URL.
 */

/**
 * Adds a preconnect to the Google Fonts file host, which the stylesheet from
 * fonts.googleapis.com loads its fonts from, so the browser opens that
 * connection while it is still reading the stylesheet.
 *
 * @param  array  $urls          Resource hints WordPress prints for $relation_type
 * @param  string $relation_type dns-prefetch, preconnect, prefetch or prerender
 * @return array
 */
function tuleva_font_resource_hints(array $urls, string $relation_type): array
{
    if ($relation_type === 'preconnect') {
        $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin'];
    }
    return $urls;
}

/**
 * Renders an upload as an <img> with a srcset of its smaller sizes, so the
 * browser downloads one near the displayed size instead of the original.
 * An ACF image field set to return a URL gives only the original's URL; this
 * finds the attachment behind it. A URL that is not one of this site's uploads
 * renders as given.
 *
 * @param  string $url        The upload's URL
 * @param  string $size       Registered size to use as src
 * @param  array  $attributes Further attributes: class, width, height, sizes, alt
 * @return string             The <img> element, lazily loaded
 */
function tuleva_upload_image(string $url, string $size, array $attributes): string
{
    $attributes += ['alt' => '', 'loading' => 'lazy', 'decoding' => 'async'];
    $id = attachment_url_to_postid($url);
    if ($id) {
        $image = wp_get_attachment_image($id, $size, false, $attributes);
        if ($image !== '') {
            return $image;
        }
    }

    return tuleva_plain_image($url, $attributes);
}

/**
 * Renders an upload made at twice its display size (its file name ends in -2x)
 * at half its pixel size, with a srcset, so a Retina screen gets the full file
 * and the layout stays as it was. Any other URL renders as a plain <img>, as
 * the templates did before.
 *
 * @param  string $url        The upload's URL
 * @param  array  $attributes Further attributes: class, alt
 * @return string             The <img> element, lazily loaded
 */
function tuleva_hidpi_image(string $url, array $attributes): string
{
    $attributes += ['alt' => '', 'loading' => 'lazy', 'decoding' => 'async'];
    $id = preg_match('/-2x\.[a-z]+$/i', $url) ? attachment_url_to_postid($url) : 0;
    $meta = $id ? wp_get_attachment_metadata($id) : false;
    if (!$meta || empty($meta['width']) || empty($meta['height'])) {
        return tuleva_plain_image($url, $attributes);
    }

    $width = intdiv((int) $meta['width'], 2);
    $height = intdiv((int) $meta['height'], 2);
    $attributes += ['sizes' => "(min-width: {$width}px) {$width}px, 100vw"];
    $html = wp_get_attachment_image($id, 'full', false, $attributes);
    return (string) preg_replace('/ width="\d+" height="\d+"/', " width=\"$width\" height=\"$height\"", $html, 1);
}

/**
 * @param  string $url
 * @param  array  $attributes
 * @return string An <img> with only the given attributes
 */
function tuleva_plain_image(string $url, array $attributes): string
{
    $html = '<img src="' . esc_url($url) . '"';
    foreach ($attributes as $name => $value) {
        $html .= ' ' . $name . '="' . esc_attr((string) $value) . '"';
    }
    return $html . '>';
}

if (function_exists('add_filter')) {
    add_filter('wp_resource_hints', 'tuleva_font_resource_hints', 10, 2);
}
