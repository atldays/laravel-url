<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

final class SmokeTest extends TestCase
{
    public function test_it_boots_the_package_testbench_harness(): void
    {
        $this->assertTrue($this->app->bound('config'));
    }
}
