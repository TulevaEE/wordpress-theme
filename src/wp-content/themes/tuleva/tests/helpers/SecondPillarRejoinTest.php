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
    public function leadNamesTheDeadlineStillOpenInTallinnAndTheMonthSavingStarts(
        string $now,
        string $expectedLead
    ): void {
        $lead = get_second_pillar_rejoin_lead(new DateTimeImmutable($now, new DateTimeZone('Europe/Tallinn')));

        $this->assertSame($expectedLead, $lead);
    }

    public static function leadProvider(): array
    {
        $money = '<b>It\'s&nbsp;your&nbsp;money.</b> Direct part of your taxes into your own II&nbsp;pillar&nbsp;account.';

        return [
            'last minute of 31 March applies by March for September' => [
                '2026-03-31 23:59:00',
                $money . ' Apply by <b>31&nbsp;March</b> and saving starts in&nbsp;September.',
            ],
            '1 April applies by July for January' => [
                '2026-04-01 00:00:00',
                $money . ' Apply by <b>31&nbsp;July</b> and saving starts in&nbsp;January.',
            ],
            '1 August applies by November for May' => [
                '2026-08-01 00:00:00',
                $money . ' Apply by <b>30&nbsp;November</b> and saving starts in&nbsp;May.',
            ],
            '1 December applies by March for September' => [
                '2026-12-01 00:00:00',
                $money . ' Apply by <b>31&nbsp;March</b> and saving starts in&nbsp;September.',
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
        $eligible = 'If you took money out of your II pillar in 2021, you can apply now.';

        return [
            'last minute of 31 March applies by March for September' => [
                '2026-03-31 23:59:00',
                $eligible . ' Do it by <b>31&nbsp;March</b> and your II pillar starts growing again as early as September.',
            ],
            '1 April applies by July for January' => [
                '2026-04-01 00:00:00',
                $eligible . ' Do it by <b>31&nbsp;July</b> and your II pillar starts growing again as early as January.',
            ],
            '1 August applies by November for May' => [
                '2026-08-01 00:00:00',
                $eligible . ' Do it by <b>30&nbsp;November</b> and your II pillar starts growing again as early as May.',
            ],
            '1 December applies by March for September' => [
                '2026-12-01 00:00:00',
                $eligible . ' Do it by <b>31&nbsp;March</b> and your II pillar starts growing again as early as September.',
            ],
        ];
    }
}
