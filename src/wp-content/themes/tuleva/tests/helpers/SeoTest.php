<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

require_once __DIR__ . '/../support/FakeEscaping.php';
require_once __DIR__ . '/../../helpers/seo.php';

if (!function_exists('esc_attr')) {
    function esc_attr($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

/** The uploads the media library knows, by URL, and the <img> each renders as */
final class FakeUploads
{
    /** @var array<string, int> */
    public static array $ids = [];
    /** @var array<int, array{string, array}> */
    public static array $rendered = [];
    /** @var array<int, array> */
    public static array $meta = [];

    public static function reset(): void
    {
        self::$ids = [];
        self::$rendered = [];
        self::$meta = [];
    }
}

function attachment_url_to_postid($url)
{
    return FakeUploads::$ids[$url] ?? 0;
}

function wp_get_attachment_image($id, $size, $icon, $attributes)
{
    FakeUploads::$rendered[] = [$size, $attributes];
    return '<img width="1632" height="816" data-id="' . $id . '">';
}

function wp_get_attachment_metadata($id)
{
    return FakeUploads::$meta[$id] ?? false;
}

final class SeoTest extends TestCase
{
    protected function setUp(): void
    {
        FakeUploads::reset();
    }

    #[Test]
    public function preconnectsToTheFontFileHost(): void
    {
        $this->assertSame(
            ['https://example.test', ['href' => 'https://fonts.gstatic.com', 'crossorigin']],
            tuleva_font_resource_hints(['https://example.test'], 'preconnect')
        );
        $this->assertSame(
            ['//fonts.googleapis.com'],
            tuleva_font_resource_hints(['//fonts.googleapis.com'], 'dns-prefetch')
        );
    }

    #[Test]
    public function rendersAnUploadInTheRequestedSizeLazily(): void
    {
        FakeUploads::$ids['https://tuleva.ee/wp-content/uploads/2024/07/face.png'] = 42;

        $html = tuleva_upload_image(
            'https://tuleva.ee/wp-content/uploads/2024/07/face.png',
            'max-height-width-100',
            ['width' => 80, 'height' => 80, 'sizes' => '80px', 'class' => 'rounded-circle']
        );

        $this->assertSame('<img width="1632" height="816" data-id="42">', $html);
        $this->assertSame([['max-height-width-100', [
            'width' => 80, 'height' => 80, 'sizes' => '80px', 'class' => 'rounded-circle',
            'alt' => '', 'loading' => 'lazy', 'decoding' => 'async',
        ]]], FakeUploads::$rendered);
    }

    #[Test]
    public function rendersAUrlThatIsNoUploadAsGiven(): void
    {
        $html = tuleva_upload_image('https://example.test/a.png?x=1&y="2"', 'max-height-width-100', ['class' => 'c']);

        $this->assertSame(
            '<img src="https://example.test/a.png?x=1&#038;y=2" class="c" alt="" loading="lazy" decoding="async">',
            $html
        );
    }

    #[Test]
    public function rendersATwiceSizeUploadAtHalfItsPixelSize(): void
    {
        $url = 'https://tuleva.ee/wp-content/uploads/2026/11/founding-members-2x.jpg';
        FakeUploads::$ids[$url] = 38102;
        FakeUploads::$meta[38102] = ['width' => 1632, 'height' => 816];

        $html = tuleva_hidpi_image($url, ['class' => 'img-fluid']);

        $this->assertSame('<img width="816" height="408" data-id="38102">', $html);
        $this->assertSame([['full', [
            'class' => 'img-fluid', 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async',
            'sizes' => '(min-width: 816px) 816px, 100vw',
        ]]], FakeUploads::$rendered);
    }

    #[Test]
    public function rendersAnyOtherImageAsBefore(): void
    {
        $url = 'https://tuleva.ee/wp-content/uploads/2018/04/founding-members@2x.jpg';
        FakeUploads::$ids[$url] = 18699;
        FakeUploads::$meta[18699] = ['width' => 816, 'height' => 408];

        $this->assertSame(
            '<img src="' . $url . '" class="img-fluid" alt="" loading="lazy" decoding="async">',
            tuleva_hidpi_image($url, ['class' => 'img-fluid'])
        );
        $this->assertSame([], FakeUploads::$rendered);
    }
}
