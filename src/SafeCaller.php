<?php

declare(strict_types=1);

namespace PeibinLaravel\Support;

use Closure;
use Illuminate\Contracts\Container\Container;
use PeibinLaravel\Contracts\StdoutLoggerInterface;
use Psr\Log\LogLevel;
use Throwable;

class SafeCaller
{
    public function __construct(private Container $container)
    {
    }

    public function call(Closure $closure, ?Closure $default = null, string $level = LogLevel::CRITICAL): mixed
    {
        try {
            return $closure();
        } catch (Throwable $exception) {
            if (
                $this->container->has(StdoutLoggerInterface::class) &&
                $logger = $this->container->get(StdoutLoggerInterface::class)
            ) {
                $logger->log($level, (string)$exception);
            }
        }

        return value($default);
    }
}
