<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

final class WithdrawLedger
{
    private $config;
    public array $withdrawals = [];

    private const USER_ID = 'user_id';
    private const TRANSACTION_AT = 'transaction_at';
    private const AMOUNT = 'amount';

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    private function getStartAndEndDayOfTheWeek(): array
    {
        $startDayOfWeek = $this->config->get('week.start_day', 'monday');
        $endDayOfWeek = $this->config->get('week.end_day', 'sunday');

        return [
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($startDayOfWeek))),
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($endDayOfWeek))),
        ];
    }

    private function transactionsInAWeek(int $userId, string $date): array
    {
        [$startDayOfWeek, $endDayOfWeek] = $this->getStartAndEndDayOfTheWeek();

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

    public function withdrawInAWeek(int $userId, string $date): int
    {
        return count($this->transactionsInAWeek($userId, $date));
    }

    public function withdrawAmountInAWeek(int $userId, string $date): float
    {
        return array_sum(
            array_column(
                $this->transactionsInAWeek($userId, $date),
                self::AMOUNT
            )
        );
    }
}
