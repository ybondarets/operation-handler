<?php

namespace App\Dto;

/**
 * Class KeyAwareDto
 *
 * @package App\Dto
 */
abstract class KeyAwareDto implements KeyAwareInterface
{
    /** @var string  */
    private string $key;

    /**
     * Currency constructor.
     *
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
}
