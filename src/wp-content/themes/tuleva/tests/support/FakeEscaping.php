<?php
declare(strict_types=1);

/**
 * esc_url() as WordPress writes a URL for display, near enough to assert what it does to one: the
 * characters a URL cannot hold, a double quote among them, are dropped, & and ' are written as
 * entities, and a URL with a protocol WordPress does not allow (javascript:) becomes ''. Every test
 * that renders a URL requires this one file: PHPUnit loads all test files into one process, and a
 * function can be declared only once.
 */
function esc_url($url)
{
    $url = str_replace(' ', '%20', ltrim((string) $url));
    $url = (string) preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\[\]\x80-\xff]|i', '', $url);

    if ($url === '') {
        return '';
    }

    if (preg_match('/^([^:\/?#]+):/', $url, $scheme)
        && !in_array(strtolower($scheme[1]), ['http', 'https', 'mailto', 'tel'], true)) {
        return '';
    }

    return str_replace(['&', "'"], ['&#038;', '&#039;'], $url);
}
