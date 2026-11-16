<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return $text;
    }
}

if (!function_exists('_x')) {
    function _x($text, $context, $domain = null)
    {
        return $text;
    }
}

function get_sub_field(string $name): string
{
    return NoticeTemplateTest::$subFields[$name];
}

function the_sub_field(string $name): void
{
    echo get_sub_field($name);
}

final class NoticeTemplateTest extends TestCase
{
    private const SUB_FIELDS = [
        'component_id' => '',
        'text' => 'Took money out of your II pillar? You can now start saving again.',
        'url' => '/en/rejoining-the-second-pillar/',
        'link_text' => 'Read more',
    ];

    public static array $subFields = self::SUB_FIELDS;

    protected function setUp(): void
    {
        self::$subFields = self::SUB_FIELDS;
    }

    #[Test]
    public function readsAsASentenceFollowedByAShortLink(): void
    {
        $html = $this->renderNotice();

        $this->assertSame(
            'Took money out of your II pillar? You can now start saving again. Read more',
            trim(preg_replace('/\s+/', ' ', strip_tags($html)))
        );
        $this->assertStringContainsString(
            '<a href="/en/rejoining-the-second-pillar/" class="text-nowrap">Read more</a>',
            $html
        );
    }

    #[Test]
    public function namesTheNextFilingDeadlineBeforeTheLinkSoTheSentenceEndsOnIt(): void
    {
        self::$subFields = [
            ...self::SUB_FIELDS,
            'text' => 'Took money out of your II pillar? By {deadline} you can',
            'link_text' => 'rejoin the II pillar',
        ];

        $html = $this->renderNotice();

        $this->assertStringContainsString(
            'By ' . get_second_pillar_rejoin_deadline_label(get_current_time_tallinn()) . ' you can',
            $html
        );
        $this->assertStringEndsWith('rejoin the II pillar</a>', trim(strip_tags($html, '<a>')));
    }

    #[Test]
    public function doesNotInterruptScreenReadersBecauseNothingAboutItIsUrgent(): void
    {
        $this->assertStringNotContainsString('role="alert"', $this->renderNotice());
    }

    private function renderNotice(): string
    {
        ob_start();
        require __DIR__ . '/../../templates/components/notice.php';

        return ob_get_clean();
    }
}
