<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

/**
 * extras.php registers hooks and shortcodes at include time and calls a handful of
 * WordPress escaping/URL helpers. None of those exist outside WordPress, so stand
 * them in here.
 */
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $args = 1)
    {
    }
}

if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $args = 1)
    {
    }
}

if (!function_exists('add_shortcode')) {
    function add_shortcode($tag, $callback)
    {
    }
}

if (!function_exists('get_site_url')) {
    function get_site_url()
    {
        return 'https://tuleva.ee';
    }
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return $text;
    }
}

if (!function_exists('esc_url')) {
    function esc_url($url)
    {
        return htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text)
    {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

require_once __DIR__ . '/../../helpers/extras.php';

final class ReportLinkTest extends TestCase
{
    #[Test]
    #[DataProvider('reportPeriodProvider')]
    public function labelCarriesTheReportPeriod(string $url, string $expectedPeriod): void
    {
        $link = generate_report_link($url, 'Investment reports');

        $this->assertStringContainsString("Investment reports ($expectedPeriod)", $link);
    }

    public static function reportPeriodProvider(): array
    {
        return [
            'period in filename wins over upload folder' => [
                'https://tuleva.ee/wp-content/uploads/2026/08/Tuleva-Maailma-Aktsiate-Pensionifondi-investeeringute-aruanne-2026-07.pdf',
                '07.2026',
            ],
            'no period in filename falls back to the month before the upload folder' => [
                'https://tuleva.ee/wp-content/uploads/2026/08/investeeringute-aruanne.pdf',
                '07.2026',
            ],
            'upload folder January rolls back to the previous December' => [
                'https://tuleva.ee/wp-content/uploads/2026/01/investeeringute-aruanne.pdf',
                '12.2025',
            ],
        ];
    }

    #[Test]
    public function absoluteUrlKeepsItsOwnHost(): void
    {
        $link = generate_report_link(
            'https://media.example.com/wp-content/uploads/2026/08/aruanne-2026-07.pdf',
            'Investment reports'
        );

        $this->assertStringContainsString(
            'href="https://media.example.com/wp-content/uploads/2026/08/aruanne-2026-07.pdf"',
            $link
        );
    }

    #[Test]
    public function relativePathIsResolvedAgainstTheSiteUrl(): void
    {
        $link = generate_report_link('/wp-content/uploads/2026/08/aruanne-2026-07.pdf', 'Investment reports');

        $this->assertStringContainsString(
            'href="https://tuleva.ee/wp-content/uploads/2026/08/aruanne-2026-07.pdf"',
            $link
        );
    }

    #[Test]
    public function urlWithoutAnyPeriodFallsBackToThePlainLabel(): void
    {
        $link = generate_report_link('https://tuleva.ee/files/aruanne.pdf', 'Investment reports');

        $this->assertStringContainsString('>Investment reports<', $link);
    }

    #[Test]
    public function urlWithoutAPeriodOrALabelStillGetsATranslatableLabel(): void
    {
        $link = generate_report_link('https://tuleva.ee/files/aruanne.pdf');

        $this->assertStringContainsString('>Investment reports<', $link);
    }

    #[Test]
    public function urlWithNoPathAtAllDoesNotWarn(): void
    {
        $link = generate_report_link('https://tuleva.ee', 'Investment reports');

        $this->assertStringContainsString('href="https://tuleva.ee"', $link);
        $this->assertStringContainsString('>Investment reports<', $link);
    }

    #[Test]
    public function labelAndUrlAreEscaped(): void
    {
        $link = generate_report_link(
            'https://tuleva.ee/files/aruanne.pdf?a=1&b=2',
            'Reports <script>alert(1)</script>'
        );

        $this->assertStringNotContainsString('<script>', $link);
        $this->assertStringContainsString('&amp;b=2', $link);
    }
}
