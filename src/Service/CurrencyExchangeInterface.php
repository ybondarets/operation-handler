<?php

namespace App\Service;

/**
 * Interface CurrencyExchangeInterface
 *
 * @package App\Service
 */
interface CurrencyExchangeInterface
{
    /**
     * @param array $currencyCodes
     *
     * @return array
     */
    public function getRates(array $currencyCodes): array;

    /**
     * @param float  $value
     * @param string $currencyCode
     *
     * @return float
     */
    public function convertToEur(float $value, string $currencyCode): float;
}
