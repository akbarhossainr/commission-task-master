<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use DI\Container;

final class WithdrawLedger
{
    public array $withdrawals = [];

    private const USER_ID = 'user_id';
    private const TRANSACTION_AT = 'transaction_at';
    private const AMOUNT = 'amount';

    private function getStartAndEndDayOfTheWeek(Container $container): array
    {
        $startDayOfWeek = $container->get('week.start_day', 'monday');
        $endDayOfWeek = $container->get('week.end_day', 'sunday');

        return [
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($startDayOfWeek))),
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($endDayOfWeek))),
        ];
    }

    private function transactionsInAWeek(Container $container, int $userId, string $date): array
    {
        [$startDayOfWeek, $endDayOfWeek] = $this->getStartAndEndDayOfTheWeek($container);

        $carbonDate = CarbonImmutable::parse($date);
        $start = $carbonDate->startOfWeek($startDayOfWeek);
        $end = $carbonDate->endOfWeek($endDayOfWeek);

        return array_filter(
            $this->withdrawals[$userId] ?? [],
            function ($withdrawal) use ($start, $end) {
                $date = CarbonImmutable::parse($withdrawal[self::TRANSACTION_AT]);

                return $date->between($start, $end);
            }
        );
    }

    public function addToLedger(int $userId, string $date, float $amount): void
    {
        if (empty($this->withdrawals[$userId])) {
            $this->withdrawals[$userId] = [];
        }

        $this->withdrawals[$userId][] = [
            self::USER_ID => $userId,
            self::TRANSACTION_AT => $date,
            self::AMOUNT => $amount,
        ];
    }

    public function withdrawInAWeek(Container $container,int $userId, string $date): int
    {
        return count($this->transactionsInAWeek($container, $userId, $date));
    }

    public function withdrawAmountInAWeek(Container $container,int $userId, string $date): float
    {
        return array_sum(
            array_column(
                $this->transactionsInAWeek($container, $userId, $date),
                self::AMOUNT
            )
        );
    }
}
