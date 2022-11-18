<?php

namespace AkbarHossain\CommissionTask\Service;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;

class WithdrawLedger
{
    protected $config;
    public array $withdrawals = [];

    protected const USER_ID = 'user_id';
    protected const TRANSACTION_AT = 'transaction_at';
    protected const AMOUNT = 'amount';


    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    protected function getStartAndEndDayOfTheWeek(): array
    {
        $startDayOfWeek = $this->config->get('week.start_day', 'monday');
        $endDayOfWeek = $this->config->get('week.end_day', 'sunday');

        return [
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($startDayOfWeek))),
            constant(sprintf('%s::%s', CarbonInterface::class, strtoupper($endDayOfWeek))),
        ];
    }

    protected function transactionsInAWeek(int $uid, string $date): array
    {
        [$startDayOfWeek, $endDayOfWeek] = $this->getStartAndEndDayOfTheWeek();

        $carbonDate = CarbonImmutable::parse($date);
        $start = $carbonDate->startOfWeek($startDayOfWeek);
        $end = $carbonDate->endOfWeek($endDayOfWeek);

        return array_filter(
            $this->withdrawals[$uid] ?? [],
            function ($withdrawal) use ($start, $end) {
                $date = CarbonImmutable::parse($withdrawal[self::TRANSACTION_AT]);

                return $date->between($start, $end);
            }
        );
    }

    public function addWithdrawal(int $uid, string $date, float $amount): void
    {
        if (empty($this->withdrawals[$uid])) {
            $this->withdrawals[$uid] = [];
        }

        $this->withdrawals[$uid][] = [
            self::USER_ID => $uid,
            self::TRANSACTION_AT => $date,
            self::AMOUNT => $amount,
        ];
    }

    public function withdrawInAWeek(int $uid, string $date): int
    {
        return count($this->transactionsInAWeek($uid, $date));
    }

    public function withdrawAmountInAWeek(int $uid, string $date): float
    {
        return array_sum(
            array_column(
                $this->transactionsInAWeek($uid, $date),
                self::AMOUNT
            )
        );
    }
}
