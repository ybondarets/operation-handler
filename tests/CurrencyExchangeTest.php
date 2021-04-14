<?php

namespace App\Tests;

use App\Service\CurrencyExchange;
use PHPUnit\Framework\TestCase;

/**
 * Class CurrencyExchangeTest
 *
 * @package App\Tests
 */
class CurrencyExchangeTest extends TestCase
{
    public function testToEurConverting(): void
    {
        $exchanger = $this->createExchangeService();

        /**
         * Because IRR one of the cheapest currencies
         */
        $eur = $exchanger->convertToEur(125, 'IRR');

        $this->assertTrue($eur < 125);
    }

    /**
     * @return CurrencyExchange
     */
    private function createExchangeService(): CurrencyExchange
    {
        return new CurrencyExchange('http://api.exchangeratesapi.io/v1/latest?', '2535cc5f7354043db3a59298331b0756');
    }
}
