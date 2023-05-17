<?php

declare(strict_types=1);

namespace PeibinLaravel\Support;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider;
use PeibinLaravel\Contracts\ExceptionFormatter\FormatterInterface;
use PeibinLaravel\Contracts\StdoutLoggerInterface;
use PeibinLaravel\Support\ExceptionFormatter\DefaultFormatter;

class SupportServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $dependencies = [
            StdoutLoggerInterface::class => StdoutLogger::class,
            FormatterInterface::class    => DefaultFormatter::class,
        ];
        $this->registerDependencies($dependencies);
    }

    private function registerDependencies(array $dependencies)
    {
        $config = $this->app->get(Repository::class);
        foreach ($dependencies as $abstract => $concrete) {
            $concreteStr = is_string($concrete) ? $concrete : gettype($concrete);
            if (is_string($concrete) && method_exists($concrete, '__invoke')) {
                $concrete = function () use ($concrete) {
                    return $this->app->call($concrete . '@__invoke');
                };
            }
            $this->app->singleton($abstract, $concrete);
            $config->set(sprintf('dependencies.%s', $abstract), $concreteStr);
        }
    }
}
