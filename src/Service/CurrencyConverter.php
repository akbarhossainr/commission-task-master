<?php

namespace AkbarHossain\CommissionTask\Service;

class CurrencyConverter
{
    public function getExchangeRates()
    {
        $curlConnection = curl_init();

        curl_setopt($curlConnection, CURLOPT_URL, 'https://developers.paysera.com/tasks/api/currency-exchange-rates');
        curl_setopt($curlConnection, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlConnection);
        curl_close($curlConnection);

        return json_decode($response);
    }

    public function convert(float $amount, string $currencyFrom, string $currencyTo)
    {
        var_dump($amount, $currencyFrom, $currencyTo, $this->getExchangeRateByCurrency($currencyTo));
        if ($currencyFrom === $this->getBaseCurrency()) {
            return $amount * $this->getExchangeRateByCurrency($currencyTo);
        }

        return round($amount * (1 / $this->getExchangeRateByCurrency($currencyFrom)), 2);
    }
}
