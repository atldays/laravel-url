<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Url;
use Illuminate\Http\Request;

final class RequestMacrosTest extends TestCase
{
    public function test_get_url_from_header_returns_wrapped_url(): void
    {
        $request = Request::create('/', 'GET', server: [
            'HTTP_ORIGIN' => 'https://example.com/path?x=1',
        ]);

        $url = $request->getUrlFromHeader('origin');

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame('https://example.com/path?x=1', (string)$url);
    }

    public function test_get_url_from_header_returns_default_for_ip_host(): void
    {
        $default = Url::fromString('https://fallback.test');
        $request = Request::create('/', 'GET', server: [
            'HTTP_ORIGIN' => 'http://127.0.0.1/test',
        ]);

        $this->assertSame($default, $request->getUrlFromHeader('origin', $default));
    }

    public function test_get_origin_url_uses_origin_header(): void
    {
        $request = Request::create('/', 'GET', server: [
            'HTTP_ORIGIN' => 'https://origin.example',
        ]);

        $this->assertSame('https://origin.example', (string)$request->getOriginUrl());
    }

    public function test_get_referer_url_uses_referer_header(): void
    {
        $request = Request::create('/', 'GET', server: [
            'HTTP_REFERER' => 'https://referer.example/path',
        ]);

        $this->assertSame('https://referer.example/path', (string)$request->getRefererUrl());
    }

    public function test_get_full_url_returns_package_url_instance(): void
    {
        $request = Request::create('https://example.com/products?sku=1', 'GET');

        $url = $request->getFullUrl();

        $this->assertInstanceOf(Url::class, $url);
        $this->assertSame('https://example.com/products?sku=1', (string)$url);
    }
}
