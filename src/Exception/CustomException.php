<?php

namespace App\Exception;

use App\Enum\ExceptionEnum;
use Exception;
use Throwable;

class CustomException extends Exception
{
    private ExceptionEnum $exceptionEnum;
    public function __construct(ExceptionEnum $exceptionEnum, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($exceptionEnum->value, $code, $previous);
        $this->exceptionEnum = $exceptionEnum;
    }

    public function getException(): ExceptionEnum
    {
        return $this->exceptionEnum;
    }
}
