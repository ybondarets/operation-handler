<?php

namespace App\Handler;

use App\Dto\OperationDto;
use App\Dto\OperationType;

/**
 * Class DepositHandler
 *
 * @package App\Handler
 */
class DepositHandler implements CommissionHandlerInterface
{
    /** @var float */
    private const DEPOSIT_COMMISSION = 0.03;

    /**
     * @param OperationDto $dto
     *
     * @return bool
     */
    public function support(OperationDto $dto): bool
    {
        return $dto->getOperationType() === OperationType::DEPOSIT;
    }

    /**
     * @param OperationDto $dto
     * @param Commission   $commission
     */
    public function handle(OperationDto $dto, Commission $commission): void
    {
        $commission->setValue(
            $dto->getAmount() * (self::DEPOSIT_COMMISSION / 100)
        );
    }
}
