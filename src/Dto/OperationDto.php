<?php

namespace App\Dto;

use \DateTime;

/**
 * Class OperationDto
 *
 * @package App\Dto
 */
class OperationDto
{
    /** @var DateTime */
    private DateTime $date;

    /** @var int */
    private int $userId;

    /** @var string */
    private string $userType;

    /** @var string */
    private string $operationType;

    /** @var float */
    private float $amount;

    /** @var string */
    private string $currencyKey;

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * @return string
     */
    public function getOperationType(): string
    {
        return $this->operationType;
    }

    /**
     * @param string $operationType
     */
    public function setOperationType(string $operationType): void
    {
        $this->operationType = $operationType;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string
     */
    public function getCurrencyKey(): string
    {
        return $this->currencyKey;
    }

    /**
     * @param string $currencyKey
     */
    public function setCurrencyKey(string $currencyKey): void
    {
        $this->currencyKey = $currencyKey;
    }

}
