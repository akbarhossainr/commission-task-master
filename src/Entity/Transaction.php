<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Entity;

use AkbarHossain\CommissionTask\Library\CurrencyRate;
use AkbarHossain\CommissionTask\Library\DefaultCurrencyRate;
use AkbarHossain\CommissionTask\Service\ContainerContract;

final class Transaction
{
    private ContainerContract $container;
    private string $transactionAt;
    private int $userId;
    private string $client;
    private string $operationType;
    private float $amount;
    private string $currency;

    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    private function getCurrencyRateService(): CurrencyRate
    {
        return $this->container->get(DefaultCurrencyRate::class);
    }

    public function getTransactionAt(): string
    {
        return $this->transactionAt;
    }

    public function setTransactionAt(string $date): self
    {
        $this->transactionAt = $date;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $id): self
    {
        $this->userId = $id;

        return $this;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function setClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getOperationType(): string
    {
        return $this->operationType;
    }

    public function setOperationType(string $type): self
    {
        $this->operationType = $type;

        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $value): self
    {
        $this->amount = floatval($value);

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $value): self
    {
        $this->currency = strtoupper($value);

        return $this;
    }

    public function getAmountInBaseCurrency(): float
    {
        $rate = $this->container->get('base_currency.rate');

        if ($this->getCurrency() !== $this->container->get('base_currency')) {
            /** @var CurrencyRate $currencyRate */
            $currencyRate = $this->getCurrencyRateService();
            $rate = $currencyRate->getRates()[$this->getCurrency()] ?? 1;
        }

        return $this->getAmount() * $rate;
    }

    public function build(array $data): self
    {
        $obj = new self($this->container);

        $obj->setTransactionAt($data['transaction_at'])
            ->setUserId((int) $data['user_id'])
            ->setClient($data['client'])
            ->setOperationType($data['operation_type'])
            ->setAmount((float) $data['amount'])
            ->setCurrency($data['currency'])
        ;

        return $obj;
    }
}
