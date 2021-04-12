<?php

namespace App\Handler;

use App\Dto\OperationDto;
use App\Dto\OperationType;
use App\Dto\UserType;

/**
 * Class BusinessWithdrawHandler
 *
 * @package App\Handler
 */
class BusinessWithdrawHandler implements CommissionHandlerInterface
{
    /** @var float */
    private const BUSINESS_WITHDRAW_COMMISSION = 0.5;

    /**
     * @param OperationDto $dto
     *
     * @return bool
     */
    public function support(OperationDto $dto): bool
    {
        return $dto->getOperationType() === OperationType::WITHDRAW && $dto->getUserType() === UserType::BUSINESS;
    }

    /**
     * @param OperationDto $dto
     * @param Commission   $commission
     */
    public function handle(OperationDto $dto, Commission $commission): void
    {
        $commission->setValue(
            $dto->getAmount() * (self::BUSINESS_WITHDRAW_COMMISSION / 100)
        );
    }
}
