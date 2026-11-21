<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

require_once __DIR__ . '/../support/FakeTranslations.php';

require_once __DIR__ . '/../../helpers/second-pillar-rejoin.php';

final class SecondPillarRejoinTest extends TestCase
{
    #[Test]
    #[DataProvider('leadProvider')]
    public function leadNamesTheDeadlineStillOpenInTallinnHighlighted(
        string $now,
        string $expectedLead
    ): void {
        $lead = get_second_pillar_rejoin_lead(new DateTimeImmutable($now, new DateTimeZone('Europe/Tallinn')));

        $this->assertSame($expectedLead, $lead);
    }

    public static function leadProvider(): array
    {
        $money = 'See how much of your taxes you can direct into your own II&nbsp;pillar&nbsp;account.';

        return [
            'last minute of 31 March applies by March for September' => [
                '2026-03-31 23:59:00',
                $money . ' <b class="text-highlight text-nowrap">Deadline 31&nbsp;March.</b>',
            ],
            '1 April applies by July for January' => [
                '2026-04-01 00:00:00',
                $money . ' <b class="text-highlight text-nowrap">Deadline 31&nbsp;July.</b>',
            ],
            '1 August applies by November for May' => [
                '2026-08-01 00:00:00',
                $money . ' <b class="text-highlight text-nowrap">Deadline 30&nbsp;November.</b>',
            ],
            '1 December applies by March for September' => [
                '2026-12-01 00:00:00',
                $money . ' <b class="text-highlight text-nowrap">Deadline 31&nbsp;March.</b>',
            ],
        ];
    }

    #[Test]
    #[DataProvider('deadlineLabelProvider')]
    public function deadlineLabelNamesTheDeadlineStillOpenInTallinn(string $now, string $expectedLabel): void
    {
        $label = get_second_pillar_rejoin_deadline_label(new DateTimeImmutable($now, new DateTimeZone('Europe/Tallinn')));

        $this->assertSame($expectedLabel, $label);
    }

    public static function deadlineLabelProvider(): array
    {
        return [
            'last minute of 31 March' => ['2026-03-31 23:59:00', '31&nbsp;March'],
            '1 April' => ['2026-04-01 00:00:00', '31&nbsp;July'],
            '1 August' => ['2026-08-01 00:00:00', '30&nbsp;November'],
            '1 December' => ['2026-12-01 00:00:00', '31&nbsp;March'],
        ];
    }

    #[Test]
    public function fillsTheDeadlinePlaceholderSoTextNeverNamesAPassedDeadline(): void
    {
        $now = new DateTimeImmutable('2026-12-01 00:00:00', new DateTimeZone('Europe/Tallinn'));

        $this->assertSame(', deadline 31&nbsp;March', with_second_pillar_rejoin_deadline(', deadline {deadline}', $now));
    }

    #[Test]
    #[DataProvider('deadlineLabelProvider')]
    public function untilFormNamesTheSameDeadlineSoTextCanLeadWithIt(string $now, string $expectedLabel): void
    {
        $label = get_second_pillar_rejoin_deadline_until_label(new DateTimeImmutable($now, new DateTimeZone('Europe/Tallinn')));

        $this->assertSame($expectedLabel, $label);
    }

    #[Test]
    public function fillsTheUntilPlaceholderSoTextCanNameTheDeadlineBeforeTheLink(): void
    {
        $now = new DateTimeImmutable('2026-12-01 00:00:00', new DateTimeZone('Europe/Tallinn'));

        $this->assertSame('31&nbsp;March you can', with_second_pillar_rejoin_deadline('{deadline_until} you can', $now));
    }

    #[Test]
    public function leavesTextWithoutThePlaceholderAsItIs(): void
    {
        $now = new DateTimeImmutable('2026-11-16 12:00:00', new DateTimeZone('Europe/Tallinn'));

        $this->assertSame('', with_second_pillar_rejoin_deadline('', $now));
        $this->assertSame('. Read more', with_second_pillar_rejoin_deadline('. Read more', $now));
    }

    #[Test]
    #[DataProvider('closingLeadProvider')]
    public function closingLeadNamesTheSameDeadlineAsTheHeroAndTheMonthTheIiPillarStartsGrowing(
        string $now,
        string $expectedLead
    ): void {
        $lead = get_second_pillar_rejoin_closing_lead(new DateTimeImmutable($now, new DateTimeZone('Europe/Tallinn')));

        $this->assertSame($expectedLead, $lead);
    }

    public static function closingLeadProvider(): array
    {
        $eligible = '<span class="rejoin-cta-line">If you submitted your withdrawal application by November 2021, you can start saving again from ';

        return [
            'last minute of 31 March applies by March for September' => [
                '2026-03-31 23:59:00',
                $eligible . 'September.</span><br><span class="rejoin-cta-line">To do that, apply by <b>31&nbsp;March</b>.</span>',
            ],
            '1 April applies by July for January' => [
                '2026-04-01 00:00:00',
                $eligible . 'next January.</span><br><span class="rejoin-cta-line">To do that, apply by <b>31&nbsp;July</b>.</span>',
            ],
            '1 August applies by November for May' => [
                '2026-08-01 00:00:00',
                $eligible . 'next May.</span><br><span class="rejoin-cta-line">To do that, apply by <b>30&nbsp;November</b>.</span>',
            ],
            '1 December applies by March for September' => [
                '2026-12-01 00:00:00',
                $eligible . 'September.</span><br><span class="rejoin-cta-line">To do that, apply by <b>31&nbsp;March</b>.</span>',
            ],
        ];
    }
}
