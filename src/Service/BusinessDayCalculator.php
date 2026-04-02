<?php

declare(strict_types=1);

namespace App\Service;

final class BusinessDayCalculator
{
    public function countWeekdaysInMonth(string $referenceMonth): int
    {
        if (!preg_match('/^\d{4}\-\d{2}$/', $referenceMonth)) {
            return 0;
        }

        [$year, $month] = array_map('intval', explode('-', $referenceMonth));
        if ($month < 1 || $month > 12) {
            return 0;
        }

        $daysInMonth = (int) (new \DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month)))->format('t');
        $count = 0;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $weekDay = (int) (new \DateTimeImmutable(sprintf('%04d-%02d-%02d', $year, $month, $day)))->format('N');
            if ($weekDay <= 5) {
                $count++;
            }
        }

        return $count;
    }
}
