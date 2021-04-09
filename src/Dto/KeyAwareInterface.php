<?php

namespace App\Dto;

/**
 * Interface KeyAwareInterface
 *
 * @package App\Dto
 */
interface KeyAwareInterface
{
    /**
     * @return string
     */
    public function getKey(): string;
}