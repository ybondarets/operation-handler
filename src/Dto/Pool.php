<?php

namespace App\Dto;

/**
 * Class Pool
 *
 * @package App\Service
 */
class Pool
{
    /** @var array */
    private iterable $currencies;

    /** @var array */
    private array $keys;

    /**
     * CurrencyPool constructor.
     *
     * @param iterable $currencies
     */
    public function __construct(iterable $currencies)
    {
        $this->currencies = $currencies;
        $this->initKeys();
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    private function initKeys(): void
    {
        /** @var KeyAwareDto $currency */
        foreach ($this->currencies as $currency) {
            $this->keys[] = $currency->getKey();
        }
    }
}
