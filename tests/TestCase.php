<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\UrlServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        $providers = [
            UrlServiceProvider::class,
        ];

        if (class_exists(LaravelDataServiceProvider::class)) {
            $providers[] = LaravelDataServiceProvider::class;
        }

        return $providers;
    }
}
