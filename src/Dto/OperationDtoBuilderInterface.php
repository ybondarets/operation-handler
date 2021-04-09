<?php

namespace App\Dto;

/**
 * Interface OperationDtoBuilderInterface
 *
 * @package App\Dto
 */
interface OperationDtoBuilderInterface
{
    public function build(array $data): OperationDto;
}
