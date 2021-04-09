<?php

namespace App\Dto;

/**
 * Class OperationType
 *
 * @package App\Dto
 */
class OperationType extends KeyAwareDto
{
    /** @var string */
    public const DEPOSIT = 'deposit';

    /** @var string */
    public const WITHDRAW = 'withdraw';
}
