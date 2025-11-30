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
    #[DataProvider('yearEndDeadlineProvider')]
    public function yearEndDeadlineCalculatesCorrectly(int $year, string $expectedDate): void
    {
        $deadline = get_year_end_deadline($year);

        $this->assertSame($expectedDate, $deadline->format('Y-m-d'));
    }

    public static function yearEndDeadlineProvider(): array
    {
        return [
            '2024: Dec 31 is Tuesday' => [2024, '2024-12-27'],
            '2023: Dec 31 is Sunday' => [2023, '2023-12-28'],
            '2025: Dec 31 is Wednesday' => [2025, '2025-12-29'],
        ];
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