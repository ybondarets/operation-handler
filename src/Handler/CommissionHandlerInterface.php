<?php

namespace App\Handler;

use App\Dto\OperationDto;

/**
 * Interface CommissionHandlerInterface
 *
 * @package App\Handler
 */
interface CommissionHandlerInterface
{
    /**
     * @param OperationDto $dto
     *
     * @return bool
     */
    public function support(OperationDto $dto): bool;

    /**
     * @param OperationDto $dto
     * @param Commission   $commission
     */
    public function handle(OperationDto $dto, Commission $commission): void;
}
