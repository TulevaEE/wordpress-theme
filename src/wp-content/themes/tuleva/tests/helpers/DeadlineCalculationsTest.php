<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;

require_once __DIR__ . '/../../helpers/deadline-calculations.php';

final class DeadlineCalculationsTest extends TestCase
{
    #[Test]
    public function tallinnTimezoneReturnsCorrectTimezone(): void
    {
        $timezone = get_tallinn_timezone();

        $this->assertInstanceOf(DateTimeZone::class, $timezone);
        $this->assertSame('Europe/Tallinn', $timezone->getName());
    }

    #[Test]
    public function currentTimeTallinnReturnsDateTimeInTallinn(): void
    {
        $time = get_current_time_tallinn();

        $this->assertInstanceOf(DateTime::class, $time);
        $this->assertSame('Europe/Tallinn', $time->getTimezone()->getName());
    }

    #[Test]
    public function currentYearTallinnReturnsInteger(): void
    {
        $year = get_current_year_tallinn();

        $this->assertIsInt($year);
        $this->assertGreaterThanOrEqual(2024, $year);
    }

    #[Test]
    #[DataProvider('workingDaysProvider')]
    public function calculateWorkingDaysBeforeDateSkipsWeekends(
        string $startDate,
        int $workingDays,
        string $expectedDate
    ): void {
        $timezone = get_tallinn_timezone();
        $date = new DateTime($startDate, $timezone);

        $result = calculate_working_days_before_date($date, $workingDays);

        $this->assertSame($expectedDate, $result->format('Y-m-d'));
    }

    public static function workingDaysProvider(): array
    {
        return [
            'Monday minus 5 working days' => ['2024-12-16', 5, '2024-12-09'],
            'Friday minus 1 working day' => ['2024-12-13', 1, '2024-12-12'],
            'Monday minus 1 working day' => ['2024-12-16', 1, '2024-12-13'],
            'Wednesday minus 10 working days' => ['2024-12-18', 10, '2024-12-04'],
        ];
    }

    #[Test]
    #[DataProvider('thirdPillarDeadlineProvider')]
    public function thirdPillarDeadlineCalculatesCorrectly(int $year, string $expectedDate): void
    {
        $deadline = get_third_pillar_deadline($year);

        $this->assertSame($expectedDate, $deadline->format('Y-m-d'));
        $this->assertSame('15:59:59', $deadline->format('H:i:s'));
    }

    public static function thirdPillarDeadlineProvider(): array
    {
        // 3rd pillar deadline = (last working day of year) - 2 working days at 15:59
        return [
            '2029: Dec 31 is Monday' => [2029, '2029-12-27'],
            '2024: Dec 31 is Tuesday' => [2024, '2024-12-27'],
            '2025: Dec 31 is Wednesday' => [2025, '2025-12-29'],
            '2026: Dec 31 is Thursday' => [2026, '2026-12-29'],
            '2027: Dec 31 is Friday' => [2027, '2027-12-29'],
            '2022: Dec 31 is Saturday' => [2022, '2022-12-28'],
            '2023: Dec 31 is Sunday' => [2023, '2023-12-27'],
        ];
    }

    #[Test]
    public function secondPillarHasThreeDeadlines(): void
    {
        $deadlines = get_second_pillar_deadlines(2025);

        $this->assertCount(3, $deadlines);
    }

    #[Test]
    public function secondPillarDeadlinesAreCorrectDates(): void
    {
        $deadlines = get_second_pillar_deadlines(2025);

        $this->assertSame('2025-03-31', $deadlines[0]->format('Y-m-d'));
        $this->assertSame('2025-07-31', $deadlines[1]->format('Y-m-d'));
        $this->assertSame('2025-11-30', $deadlines[2]->format('Y-m-d'));
    }

    #[Test]
    public function secondPillarDeadlinesAreAt2359(): void
    {
        $deadlines = get_second_pillar_deadlines(2025);

        foreach ($deadlines as $deadline) {
            $this->assertSame('23:59:59', $deadline->format('H:i:s'));
        }
    }

    #[Test]
    public function secondPillarDeadlinesAreInTallinnTimezone(): void
    {
        $deadlines = get_second_pillar_deadlines(2025);

        foreach ($deadlines as $deadline) {
            $this->assertSame('Europe/Tallinn', $deadline->getTimezone()->getName());
        }
    }

    #[Test]
    public function isWithinDaysBeforeDeadlineReturnsTrueWhenWithinRange(): void
    {
        $deadline = get_current_time_tallinn();
        $deadline->modify('+5 days');

        $result = is_within_days_before_deadline($deadline, 10);

        $this->assertTrue($result);
    }

    #[Test]
    public function isWithinDaysBeforeDeadlineReturnsFalseWhenPastDeadline(): void
    {
        $deadline = get_current_time_tallinn();
        $deadline->modify('-1 day');

        $result = is_within_days_before_deadline($deadline, 10);

        $this->assertFalse($result);
    }

    #[Test]
    public function isWithinDaysBeforeDeadlineReturnsFalseWhenTooFarAhead(): void
    {
        $deadline = get_current_time_tallinn();
        $deadline->modify('+30 days');

        $result = is_within_days_before_deadline($deadline, 10);

        $this->assertFalse($result);
    }

    #[Test]
    public function tallinnTimestampDiffersFromUtc(): void
    {
        $timezone = get_tallinn_timezone();
        $dateString = '2024-11-17 00:00:00';

        $tallinnTime = new DateTime($dateString, $timezone);
        $utcTime = new DateTime($dateString, new DateTimeZone('UTC'));

        $this->assertNotSame(
            $utcTime->getTimestamp(),
            $tallinnTime->getTimestamp(),
            'Tallinn timestamp should differ from UTC by 2-3 hours'
        );
    }

    #[Test]
    public function yearBoundaryHandledCorrectlyForTallinn(): void
    {
        $timezone = get_tallinn_timezone();

        // Dec 31, 2024 at 22:00 UTC = Jan 1, 2025 at 00:00 Tallinn (UTC+2 in winter)
        $utcTime = new DateTime('2024-12-31 22:00:00', new DateTimeZone('UTC'));
        $tallinnTime = clone $utcTime;
        $tallinnTime->setTimezone($timezone);

        $this->assertSame('2025', $tallinnTime->format('Y'));
        $this->assertSame('01', $tallinnTime->format('d'));
    }
}