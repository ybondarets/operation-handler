<?php

namespace App\Handler;

use App\Dto\OperationDto;

/**
 * Class OperationHandler
 *
 * @package App\Handler
 */
class CommissionHandler
{
    /** @var iterable */
    private iterable $handlers;

    /**
     * OperationHandler constructor.
     *
     * @param iterable $handlers
     */
    public function __construct(iterable $handlers = [])
    {
        $this->handlers = $handlers;
    }

    /**
     * @param OperationDto $dto
     *
     * @return Commission
     */
    public function handle(OperationDto $dto): Commission
    {
        $commission = new Commission();

        /** @var CommissionHandlerInterface $handler */
        foreach ($this->handlers as $handler) {
            if ($handler->support($dto)) {
                $handler->handle($dto, $commission);
            }
        }


        return $commission;
    }
}
