<?php

namespace App\Handler;

use App\Dto\OperationDto;
use App\Dto\OperationType;
use App\Dto\UserType;
use \DateTime;

/**
 * Class BusinessWithdrawHandler
 *
 * @package App\Handler
 */
class PrivateWithdrawHandler implements CommissionHandlerInterface
{
    /** @var float */
    private const PRIVATE_WITHDRAW_COMMISSION = 0.3;

    /** @var int */
    private const WEEKLY_AMOUNT_FREE_LIMIT = 1000;

    /** @var int */
    private const WEEKLY_OPERATIONS_COUNT_LIMIT = 3;

    /** @var array */
    private array $operationsUserHistory;

    /**
     * PrivateWithdrawHandler constructor.
     */
    public function __construct()
    {
        $this->operationsUserHistory = [];
    }

    /**
     * @param OperationDto $dto
     *
     * @return bool
     */
    public function support(OperationDto $dto): bool
    {
        return $dto->getOperationType() === OperationType::WITHDRAW && $dto->getUserType() === UserType::PRIVATE;
    }

    /**
     * @param OperationDto $dto
     * @param Commission   $commission
     */
    public function handle(OperationDto $dto, Commission $commission): void
    {

        if (!$this->isLimitsReached($dto)) {
        }


        $this->addUserOperation($dto);

        $operationAmount = $dto->getAmount();


        $commission->setValue(12);
    }

    /**
     * @param OperationDto $dto
     *
     * @return bool
     */
    private function isLimitsReached(OperationDto $dto): bool
    {
        $userId = $dto->getUserId();
        $date = $dto->getDate();

        return $this->isWeeklyOperationsCountReached($userId, $date) && $this->isWeeklyAmountLimitReached($userId, $date);
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return bool
     */
    private function isWeeklyOperationsCountReached(int $userId, DateTime $date): bool
    {
        return count($this->getUserOperationsInAWeek($userId, $date)) >= self::WEEKLY_OPERATIONS_COUNT_LIMIT;
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return bool
     */
    private function isWeeklyAmountLimitReached(int $userId, DateTime $date): bool
    {
        return $this->getWeeklyUserAmount($userId, $date) > self::WEEKLY_AMOUNT_FREE_LIMIT;
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return float
     */
    private function getWeeklyUserAmount(int $userId, DateTime $date): float
    {
        $operationsAmount = 0;
        /** @var OperationDto $operation */
        foreach ($this->getUserOperationsInAWeek($userId, $date) as $operation) {
            $operationsAmount += $operation->getAmount();
        }

        return $operationsAmount;
    }

    /**
     * @param OperationDto $dto
     */
    private function addUserOperation(OperationDto $dto): void
    {
        $this->createUserOperations($dto->getUserId());
        $this->operationsUserHistory[$dto->getUserId()][] = $dto;
    }

    /**
     * @param int $userId
     *
     * @return array
     */
    private function getUserOperationsInAWeek(int $userId, DateTime $date): array
    {
        $this->createUserOperations($userId);

        $userOperations = $this->operationsUserHistory[$userId];
        $result = [];

        $dateWeek = $date->format('oW');
        /** @var OperationDto $operation */
        foreach ($userOperations as $operation) {
            if($operation->getDate()->format('oW') === $dateWeek) {
                $result[] = $operation;
            }
        }

        return $result;
    }

    /**
     * @param int $userId
     */
    private function createUserOperations(int $userId): void
    {
        if (array_key_exists($userId, $this->operationsUserHistory) === false) {
            $this->operationsUserHistory[$userId] = [];
        }
    }
}
