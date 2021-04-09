<?php

namespace App\Dto;

use \DateTime;
use \Exception;
use Webmozart\Assert\Assert;

/**
 * Class OperationDtoBuilder
 *
 * @package App\Dto
 */
class OperationDtoBuilder implements OperationDtoBuilderInterface
{
    /** @var int  */
    protected const DATE_KEY = 0;

    /** @var int  */
    protected const USER_ID_KEY = 1;

    /** @var int  */
    protected const USER_TYPE_KEY = 2;

    /** @var int  */
    protected const OPERATION_TYPE_KEY = 3;

    /** @var int  */
    protected const AMOUNT_KEY = 4;

    /** @var int  */
    protected const CURRENCY_KEY = 5;

    /** @var Pool */
    private Pool $currencyPool;

    /** @var Pool */
    private Pool $operationTypePool;

    /** @var Pool */
    private Pool $userTypePool;

    /**
     * OperationDtoBuilder constructor.
     *
     * @param Pool $currencyPool
     * @param Pool $operationTypePool
     * @param Pool $userTypePool
     */
    public function __construct(Pool $currencyPool, Pool $operationTypePool, Pool $userTypePool)
    {
        $this->currencyPool = $currencyPool;
        $this->operationTypePool = $operationTypePool;
        $this->userTypePool = $userTypePool;
    }

    /**
     * @param array $data
     *
     * @return OperationDto
     *
     * @throws Exception
     */
    public function build(array $data): OperationDto
    {
        $this->validate($data);

        $dto = $this->createDto();
        $dto->setDate(new DateTime($data[static::DATE_KEY]));
        $dto->setUserId((int) $data[static::USER_ID_KEY]);
        $dto->setUserType($data[static::USER_TYPE_KEY]);
        $dto->setOperationType($data[static::OPERATION_TYPE_KEY]);
        $dto->setAmount((float) $data[static::AMOUNT_KEY]);
        $dto->setCurrencyKey($data[static::CURRENCY_KEY]);

        return $dto;
    }

    /**
     * @param array $data
     */
    private function validate(array $data): void
    {
        $this->validateLength($data);
        $this->validateDate($data[static::DATE_KEY]);
        $this->validateUserId($data[static::USER_ID_KEY]);
        $this->validateUserType($data[static::USER_TYPE_KEY]);
        $this->validateOperationType($data[static::OPERATION_TYPE_KEY]);
        $this->validateAmount($data[static::AMOUNT_KEY]);
        $this->validateCurrency($data[static::CURRENCY_KEY]);
    }

    /**
     * @param array $data
     */
    private function validateLength(array $data)
    {
        Assert::eq(count($data), 6);
    }

    /**
     * @param string $date
     *
     * @throws Exception
     */
    private function validateDate(string $date): void
    {
        Assert::notEmpty($date);
        \DateTime::createFromFormat('Y-m-d', $date);
        $errors = \DateTime::getLastErrors();

        if ($errors['error_count'] > 0) {
            throw new Exception('Date format is invalid [' . $date . ']');
        }
    }

    /**
     * @param string $userId
     *
     * @throws Exception
     */
    private function validateUserId(string $userId): void
    {
        Assert::notEmpty($userId);
        if (!is_numeric($userId)) {
            throw new Exception('User ID should be numeric [' . $userId . ']');
        }
    }

    /**
     * @param string $userType
     */
    private function validateUserType(string $userType): void
    {
        Assert::notEmpty($userType);
        Assert::oneOf($userType, $this->userTypePool->getKeys());
    }

    /**
     * @param string $operationType
     */
    private function validateOperationType(string $operationType): void
    {
        Assert::notEmpty($operationType);
        Assert::oneOf($operationType, $this->operationTypePool->getKeys());
    }

    /**
     * @param string $currency
     */
    private function validateCurrency(string $currency): void
    {
        Assert::notEmpty($currency);
        Assert::oneOf($currency, $this->currencyPool->getKeys());
    }

    /**
     * @param string $amount
     *
     * @throws Exception
     */
    private function validateAmount(string $amount): void
    {
        Assert::notEmpty($amount);
        if (!is_numeric($amount)) {
            throw new Exception('Amount should be numeric [' . $amount . ']');
        }
    }

    /**
     * @return OperationDto
     */
    private function createDto(): OperationDto
    {
        return new OperationDto();
    }
}
