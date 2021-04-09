<?php

namespace App\Command;

use App\Dto\OperationDtoBuilderInterface;
use App\Reader\ReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert;

/**
 * Class OperationHandlerCommand
 *
 * @package App\Command
 */
class OperationHandlerCommand extends Command
{
    /** @var ReaderInterface */
    private ReaderInterface $reader;

    /** @var OperationDtoBuilderInterface */
    private OperationDtoBuilderInterface $dtoBuilder;

    public function __construct(string $name = null, ReaderInterface $reader, OperationDtoBuilderInterface $dtoBuilder)
    {
        parent::__construct($name);

        $this->reader = $reader;
        $this->dtoBuilder = $dtoBuilder;
    }

    /**
     * Configure command to use
     */
    protected function configure()
    {
        $this
            ->setName('operation:handler')
            ->setDescription('')
            ->addArgument('input', InputArgument::REQUIRED, 'Argument description')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $input->getArgument('input');
        Assert::fileExists($inputFile);

        $inputData = $this->reader->readFile($inputFile);
        $operations = $this->createOperations($inputData);

        return Command::SUCCESS;
    }

    /**
     * @param array $operationsData
     *
     * @return array
     */
    private function createOperations(array $operationsData): array
    {
        $result = [];

        foreach ($operationsData as $operationData) {
            $result[] = $this->dtoBuilder->build($operationData);
        }

        return $result;
    }
}
