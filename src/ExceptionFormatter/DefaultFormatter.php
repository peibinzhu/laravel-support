<?php

declare(strict_types=1);

namespace PeibinLaravel\Support\ExceptionFormatter;

use PeibinLaravel\Contracts\ExceptionFormatter\FormatterInterface;
use Throwable;

class DefaultFormatter implements FormatterInterface
{
    public function format(Throwable $throwable): string
    {
        return sprintf(
            '%s: %s in %s:%s',
            get_class($throwable),
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine()
        );
    }
}
