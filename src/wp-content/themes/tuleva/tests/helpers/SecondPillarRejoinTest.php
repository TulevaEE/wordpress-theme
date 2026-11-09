<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

if (!defined('TEXT_DOMAIN')) {
    define('TEXT_DOMAIN', 'tuleva');
}

if (!function_exists('__')) {
    function __($text, $domain = null)
    {
        return $text;
    }
}

require_once __DIR__ . '/../../helpers/second-pillar-rejoin.php';

final class SecondPillarRejoinTest extends TestCase
{
    #[Test]
    #[DataProvider('leadProvider')]
    public function leadNamesTheDeadlineStillOpenInTallinn(
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
                $money . ' Apply by <b>31&nbsp;March</b>.',
            ],
            '1 April applies by July for January' => [
                '2026-04-01 00:00:00',
                $money . ' Apply by <b>31&nbsp;July</b>.',
            ],
            '1 August applies by November for May' => [
                '2026-08-01 00:00:00',
                $money . ' Apply by <b>30&nbsp;November</b>.',
            ],
            '1 December applies by March for September' => [
                '2026-12-01 00:00:00',
                $money . ' Apply by <b>31&nbsp;March</b>.',
            ],
        ];
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
