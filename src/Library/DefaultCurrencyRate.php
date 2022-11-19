<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Library;

class DefaultCurrencyRate implements CurrencyRate
{
    protected function getExchangeRates(): string
    {
        $curlConnection = curl_init();

        curl_setopt($curlConnection, CURLOPT_URL, 'https://developers.paysera.com/tasks/api/currency-exchange-rates');
        curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlConnection);
        curl_close($curlConnection);

        return $response ?? '';
    }

    public function getRates(): array
    {
        $response = json_decode($this->getExchangeRates(), true);

        return json_last_error() !== JSON_ERROR_NONE
            ? []
            : ($response['rates'] ?? []);
    }

    public function getBaseCurrency(): string
    {
        $response = json_decode($this->getExchangeRates(), true);

        return json_last_error() !== JSON_ERROR_NONE
            ? []
            : ($response['base'] ?? 'EUR');
    }
}
