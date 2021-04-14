<?php

namespace App\Handler;

use App\Dto\Currency;
use App\Dto\OperationDto;
use App\Dto\OperationType;
use App\Dto\UserType;
use App\Service\CurrencyExchangeInterface;
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

    /** @var CurrencyExchangeInterface */
    private CurrencyExchangeInterface $currencyExchange;

    /**
     * PrivateWithdrawHandler constructor.
     *
     * @param CurrencyExchangeInterface $currencyExchange
     */
    public function __construct(CurrencyExchangeInterface $currencyExchange)
    {
        $this->operationsUserHistory = [];
        $this->currencyExchange = $currencyExchange;
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
        $dto = $this->normalizeOperation($dto);
        $this->addUserOperation($dto);

        $userId = $dto->getUserId();
        $date = $dto->getDate();

        switch (true) {
            case $this->isWeeklyOperationsCountReached($userId, $date):
                $commission->setValue(
                    $this->getCommissionForValue(
                        $dto->getAmount()
                    )
                );

                break;
            case $this->isWeeklyAmountLimitReached($userId, $date):
                $amountToCharge = $this->getAmountToCharge($userId, $date, $dto->getAmount());

                $commission->setValue(
                    $this->getCommissionForValue(
                        $amountToCharge
                    )
                );

                break;
            default:
                $commission->setValue(0.0);
                break;
        }
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     * @param float    $currentValue
     *
     * @return float
     */
    private function getAmountToCharge(int $userId, DateTime $date, float $currentValue): float
    {
        $operationsAmount = $this->getWeeklyUserOperationsAmount($userId, $date);

        if ($operationsAmount <= self::WEEKLY_AMOUNT_FREE_LIMIT) {
            return 0.0;
        }

        return min($currentValue, $operationsAmount - self::WEEKLY_AMOUNT_FREE_LIMIT);
    }

    /**
     * @param float $value
     *
     * @return float
     */
    private function getCommissionForValue(float $value): float
    {
        return $value * (self::PRIVATE_WITHDRAW_COMMISSION / 100);
    }

    /**
     * @param OperationDto $dto
     *
     * @return OperationDto
     */
    private function normalizeOperation(OperationDto $dto): OperationDto
    {
        $result = clone $dto;

        if ($result->getCurrencyKey() !== Currency::EUR) {
            $result->setAmount(
                $this
                    ->currencyExchange
                    ->convertToEur(
                        $result->getAmount(),
                        $result->getCurrencyKey()
                    )
            );

            $result->setCurrencyKey(Currency::EUR);
        }

        return $result;
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return bool
     */
    private function isWeeklyOperationsCountReached(int $userId, DateTime $date): bool
    {
        return count($this->getUserOperationsInAWeek($userId, $date)) > self::WEEKLY_OPERATIONS_COUNT_LIMIT;
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return bool
     */
    private function isWeeklyAmountLimitReached(int $userId, DateTime $date): bool
    {
        return $this->getWeeklyUserOperationsAmount($userId, $date) > self::WEEKLY_AMOUNT_FREE_LIMIT;
    }

    /**
     * @param int      $userId
     * @param DateTime $date
     *
     * @return float
     */
    private function getWeeklyUserOperationsAmount(int $userId, DateTime $date): float
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
