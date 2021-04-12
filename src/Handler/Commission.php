<?php

namespace App\Handler;

/**
 * Class Commission
 *
 * @package App\Handler
 */
class Commission
{
    /** @var float */
    private float $value;

    /**
     * Commission constructor.
     */
    public function __construct()
    {
        $this->value = 0;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     */
    public function setValue(float $value): void
    {
        $this->value = $value;
    }
}
