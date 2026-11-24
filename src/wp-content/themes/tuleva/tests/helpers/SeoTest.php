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

    public static function reset(): void
    {
        self::$ids = [];
        self::$rendered = [];
    }
}

function attachment_url_to_postid($url)
{
    return FakeUploads::$ids[$url] ?? 0;
}

function wp_get_attachment_image($id, $size, $icon, $attributes)
{
    FakeUploads::$rendered[] = [$size, $attributes];
    return '<img data-id="' . $id . '">';
}

final class SeoTest extends TestCase
{
    protected function setUp(): void
    {
        FakeUploads::reset();
    }

    private const YOAST_SITEMAP_QUERY = "
			SELECT l.ID, post_title, post_content, post_name, post_parent, post_author, post_status, post_modified_gmt, post_date, post_date_gmt
			FROM (
				SELECT wp_posts.ID
				FROM wp_posts
				WHERE wp_posts.post_status = 'publish' AND wp_posts.post_type = 'post'
				ORDER BY wp_posts.post_modified ASC LIMIT 100 OFFSET 200
			)
			o JOIN wp_posts l ON l.ID = o.ID
		";

    #[Test]
    public function breaksTiesInTheSitemapBatchOrderByPostId(): void
    {
        $sql = tuleva_sitemap_stable_order(self::YOAST_SITEMAP_QUERY);

        $this->assertStringContainsString(
            'ORDER BY wp_posts.post_modified ASC, wp_posts.ID ASC LIMIT 100 OFFSET 200',
            $sql
        );
        $this->assertSame(
            self::YOAST_SITEMAP_QUERY,
            str_replace(', wp_posts.ID ASC', '', $sql)
        );
    }

    #[Test]
    public function leavesOtherQueriesAsTheyAre(): void
    {
        $queries = [
            "SELECT * FROM wp_posts WHERE ID = 5",
            "SELECT ID FROM wp_posts ORDER BY wp_posts.post_date DESC LIMIT 10",
            "SELECT ID FROM wp_posts ORDER BY wp_posts.post_modified DESC LIMIT 10",
        ];

        foreach ($queries as $sql) {
            $this->assertSame($sql, tuleva_sitemap_stable_order($sql));
        }
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

        $this->assertSame('<img data-id="42">', $html);
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
}
