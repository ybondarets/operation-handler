<?php

namespace App\Tests;

use App\Reader\CsvReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Webmozart\Assert\Assert;

/**
 * Class OperationHandlerCommandTest
 *
 * @package App\Tests
 */
class OperationHandlerCommandTest extends KernelTestCase
{
    public function testCommandExecutionOutput()
    {
        $commandTester = $this->createCommandTester();
        $commandTester->execute(['input' => './dummyFile.csv']);
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('0.6
3
0
0.06
1.5
0
0.69
0.25
0.3
3
0
0
66.03
', $output);
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester(): CommandTester
    {
        $kernel = self::bootKernel();

        static::$kernel->getContainer()->set('App\Reader\ReaderInterface', $this->getReaderMock());
        static::$kernel->getContainer()->set('App\Service\CurrencyExchangeInterface', $this->getCurrencyExchangerMock());

        $application = new Application($kernel);

        $command = $application->find('operation:handler');

        return new CommandTester($command);
    }

    private function getReaderMock()
    {
        $csvReader = $this->getMockBuilder('App\Reader\CsvReader')
            ->enableOriginalConstructor()
            ->setConstructorArgs([new CsvEncoder()])
            ->setMethods(['loadFile'])
            ->getMock();
        $csvReader
            ->expects(
                $this->atLeastOnce()
            )
            ->method('loadFile')
            ->will($this->returnValue('2014-12-31,4,private,withdraw,1200.0001,EUR
2015-01-01,4,private,withdraw,1000.25,EUR
2016-01-05,4,private,withdraw,1000.00,EUR
2016-01-05,1,private,deposit,200.00,EUR
2016-01-06,2,business,withdraw,300.13,EUR
2016-01-06,1,private,withdraw,30000,JPY
2016-01-07,1,private,withdraw,1000.00,EUR
2016-01-07,1,private,withdraw,100.00,USD
2016-01-10,1,private,withdraw,100.00,EUR
2016-01-10,2,business,deposit,10000.00,EUR
2016-01-10,3,private,withdraw,1000.00,EUR
2016-02-15,1,private,withdraw,300.00,EUR
2016-02-19,5,private,withdraw,3000000,JPY'));

        return $csvReader;
    }

    private function getCurrencyExchangerMock()
    {
        $exchanger = $this->getMockBuilder('App\Service\CurrencyExchange')
            ->disableOriginalConstructor()
            ->setMethods(['getRates'])
            ->getMock();
        $exchanger
            ->expects(
                $this->atLeastOnce()
            )
            ->method('getRates')
            ->will($this->returnValue([
              'USD' => 1.19585,
              'JPY' => 130.385944,
            ]));

        return $exchanger;
    }

/*
 array (
  'USD' => 1.19585,
  'JPY' => 130.385944,
)
 */
}
