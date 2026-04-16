<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Facades\Url as UrlFacade;
use Atldays\Url\Url;

final class UrlFacadeTest extends TestCase
{
    public function test_facade_creates_url_instances(): void
    {
        $url = UrlFacade::make('https://example.com/path?value=1');

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame('https://example.com/path?value=1', (string)$url);
    }
}
