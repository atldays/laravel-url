<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\UrlServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use WithWorkbench;

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            UrlServiceProvider::class,
        ];
    }
}
