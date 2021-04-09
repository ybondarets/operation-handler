<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * Class OperationHandlerCommandTest
 *
 * @package App\Tests
 */
class OperationHandlerCommandTest extends KernelTestCase
{
    /**
     * Check if input file argument is required
     */
    public function testArgumentsValidation(): void
    {
        $commandTester = $this->createCommandTester();

        $exception = null;
        try {
            $commandTester->execute([]);
        } catch (RuntimeException $exception) {
            $this->assertStringContainsString('input', $exception->getMessage());
        }

        $this->assertNotNull($exception);
    }

    /**
     * @return CommandTester
     */
    private function createCommandTester(): CommandTester
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('operation:handler');

        return new CommandTester($command);
    }
}
