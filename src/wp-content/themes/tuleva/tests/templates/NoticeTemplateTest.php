<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

function get_sub_field(string $name): string
{
    return NoticeTemplateTest::SUB_FIELDS[$name];
}

function the_sub_field(string $name): void
{
    echo get_sub_field($name);
}

final class NoticeTemplateTest extends TestCase
{
    public const SUB_FIELDS = [
        'component_id' => '',
        'text' => 'Took money out of your II pillar? You can now start saving again.',
        'url' => '/en/rejoining-the-second-pillar/',
        'link_text' => 'Read more',
    ];

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
