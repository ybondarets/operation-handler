<?php

namespace App\Dto;

/**
 * Class UserType
 *
 * @package App\Dto
 */
class UserType extends KeyAwareDto
{
    /** @var string */
    public const PRIVATE = 'private';

    /** @var string */
    public const BUSINESS = 'business';
}
