<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Url;
use PHPUnit\Framework\TestCase;

final class IpUrlTest extends TestCase
{
    public function test_is_ip(): void
    {
        $url = Url::fromString('http://233.221.22.33/');

        $this->assertTrue($url->isIp());
    }

    public function test_is_not_ip(): void
    {
        $url = Url::fromString('https://google.com');

        $this->assertFalse($url->isIp());
    }
}
