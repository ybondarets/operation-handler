<?php

namespace App\Command;

use App\Dto\OperationDto;
use App\Dto\OperationDtoBuilderInterface;
use App\Handler\CommissionHandler;
use App\Reader\ReaderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    /** @var CommissionHandler  */
    private CommissionHandler $commissionHandler;

    public function __construct(ReaderInterface $reader, OperationDtoBuilderInterface $dtoBuilder, CommissionHandler $commissionHandler)
    {
        parent::__construct();

        $this->reader = $reader;
        $this->dtoBuilder = $dtoBuilder;
        $this->commissionHandler = $commissionHandler;
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

        $inputData = $this->reader->readFile($inputFile);
        $operations = $this->createOperations($inputData);

        /** @var OperationDto $operation */
        foreach ($operations as $operation) {
            $commission = $this->commissionHandler->handle($operation);
            $output->writeln(round($commission->getValue(), 2));
        }

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
