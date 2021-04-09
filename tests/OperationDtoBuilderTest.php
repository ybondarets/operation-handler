<?php

namespace App\Tests;

use App\Dto\Currency;
use App\Dto\OperationDtoBuilder;
use App\Dto\OperationType;
use App\Dto\Pool;
use App\Dto\UserType;
use PHPUnit\Framework\TestCase;
use \Throwable;
use \DateTime;

/**
 * Class OperationDtoBuilderTest
 *
 * @package App\Tests
 */
class OperationDtoBuilderTest extends TestCase
{
    /**
     * @param array $input
     * @param string $expectedError
     *
     * @dataProvider validationExceptionsDataProvider
     */
    public function testValidationException(array $input, string $expectedError): void
    {
        $builder = $this->createDtoBuilder();

        $exception = null;
        try {
            $builder->build($input);
        } catch (Throwable $exception) {
            $this->assertEquals($expectedError, $exception->getMessage());
        }

        $this->assertNotNull($exception);
    }

    /**
     * @return array[]
     */
    public function validationExceptionsDataProvider()
    {
        return [
            [
                [],
                'Expected a value equal to 6. Got: 0'
            ],
            [
                ['12','','','','',''],
                'Date format is invalid [12]'
            ],
            [
                ['2021-12-28','nn','','','',''],
                'User ID should be numeric [nn]'
            ],
            [
                ['2021-12-28','12','manager','','',''],
                'Expected one of: "business". Got: "manager"'
            ],
            [
                ['2021-12-28','12','business','credit','',''],
                'Expected one of: "deposit". Got: "credit"'
            ],
            [
                ['2021-12-28','12','business','deposit','a',''],
                'Amount should be numeric [a]'
            ],
            [
                ['2021-12-28','12','business','deposit','123.432','JPY'],
                'Expected one of: "EUR", "USD". Got: "JPY"'
            ],
        ];
    }

    public function testBuildDto()
    {
        $builder = $this->createDtoBuilder();
        $dto = $builder->build(
            [
                '2012-12-28',
                '12',
                'business',
                'deposit',
                '123.00134',
                'EUR'
            ]
        );

        $this->assertEquals(new DateTime('2012-12-28'), $dto->getDate());
        $this->assertEquals(12, $dto->getUserId());
        $this->assertEquals('business', $dto->getUserType());
        $this->assertEquals('deposit', $dto->getOperationType());
        $this->assertEquals(123.00134, $dto->getAmount());
        $this->assertEquals('EUR', $dto->getCurrencyKey());
    }

    /**
     * @return OperationDtoBuilder
     */
    private function createDtoBuilder(): OperationDtoBuilder
    {
        return new OperationDtoBuilder(
            new Pool([new Currency('EUR'), new Currency('USD')]),
            new Pool([new OperationType('deposit')]),
            new Pool([new UserType('business')]),
        );
    }
}
