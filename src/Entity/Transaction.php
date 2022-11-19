<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Entity;

use AkbarHossain\CommissionTask\Service\Config;
use AkbarHossain\CommissionTask\Service\CurrencyRate\CurrencyRate;

final class Transaction
{
    private $config;
    private $transactionAt;
    private $userId;
    private $client;
    private $operationType;
    private $amount;
    private $currency;
    private $amountInBaseCurrency;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    private function getCurrencyRateService(): CurrencyRate
    {
        return $this->config->get('currency_rate');
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
        $this->amount = $value;

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
        $rate = $this->config->get('base_currency.rate', 1);

        if ($this->getCurrency() !== $this->config->get('base_currency', 'EUR')) {
            /** @var CurrencyRate $currencyRate */
            $currencyRate = $this->getCurrencyRateService();
            $rate = $currencyRate->getRates()[$this->getCurrency()];
        }

        return $this->getAmount() * $rate;
    }

    public function setAmountInBaseCurrency(float $value): self
    {
        $this->amountInBaseCurrency = $value;

        return $this;
    }

    public function build(array $data): self
    {
        $obj = new self($this->config);

        $obj->setTransactionAt($data['transaction_at'])
            ->setUserId((int) $data['user_id'])
            ->setClient($data['client'])
            ->setOperationType($data['operation_type'])
            ->setAmount((float) $data['amount'])
            ->setCurrency($data['currency']);

        return $obj;
    }
}
