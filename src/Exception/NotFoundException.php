<?php

namespace App\Exception;

use App\Enum\ExceptionEnum;
use Throwable;

class NotFoundException extends CustomException
{
    public function __construct(ExceptionEnum $exceptionEnum, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($exceptionEnum, $code, $previous);
    }
}
