<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Contracts\Url as UrlContract;
use Atldays\Url\Url;
use Atldays\Url\UrlFactory;
use Spatie\Url\Exceptions\InvalidArgument;

final class WrapTest extends TestCase
{
    private UrlFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->factory = $this->app->make(UrlFactory::class);
    }

    public function test_wraps_full_url(): void
    {
        $link = 'https://www.example.com/somepackage/somefile.php?somequery=somevalue#somefragment';
        $url = $this->factory->make($link);

        $this->assertSame($link, (string)$url);
    }

    public function test_rejects_invalid_schema(): void
    {
        $this->expectException(InvalidArgument::class);

        $this->factory->make('schema://example.com');
    }

    public function test_wrap_scrubs_invalid_utf8(): void
    {
        $url = $this->factory->make("https://example.com/\xC3\x28");

        $this->assertTrue(mb_check_encoding((string)$url, 'UTF-8'));
        $this->assertSame('https', $url->getScheme());
        $this->assertSame('example.com', $url->getHost());
        $this->assertStringStartsWith('https://example.com', (string)$url);
    }

    public function test_wrap_or_null_returns_null_on_invalid_input(): void
    {
        $this->assertNull($this->factory->makeOrNull('schema://example.com'));
        $this->assertNull($this->factory->makeOrNull(null));
    }

    public function test_wrap_rewraps_foreign_url_contract_instances(): void
    {
        $foreignUrl = new class implements UrlContract
        {
            public function __toString(): string
            {
                return 'https://example.com/path?value=1';
            }

            public function isIp(): bool
            {
                return false;
            }

            public function hasBrowserSpecificScheme(): bool
            {
                return false;
            }

            public function isBrowserSpecific(): bool
            {
                return false;
            }

            public function getBase(): string
            {
                return 'https://example.com';
            }

            public function getScheme(): string
            {
                return 'https';
            }

            public function getAuthority(): string
            {
                return 'example.com';
            }

            public function getUserInfo(): string
            {
                return '';
            }

            public function getHost(): string
            {
                return 'example.com';
            }

            public function getPort(): ?int
            {
                return null;
            }

            public function getPath(): string
            {
                return '/path';
            }

            public function getQuery(): string
            {
                return 'value=1';
            }

            public function getFragment(): string
            {
                return '';
            }

            public function withScheme($scheme): static
            {
                return $this;
            }

            public function withUserInfo($user, $password = null): static
            {
                return $this;
            }

            public function withHost($host): static
            {
                return $this;
            }

            public function withPort($port): static
            {
                return $this;
            }

            public function withPath($path): static
            {
                return $this;
            }

            public function withQuery($query): static
            {
                return $this;
            }

            public function withFragment($fragment): static
            {
                return $this;
            }

            public function getSegments(): array
            {
                return ['path'];
            }

            public function getSegment(int $index, mixed $default = null): mixed
            {
                return 'path';
            }

            public function getFirstSegment(): mixed
            {
                return 'path';
            }

            public function getLastSegment(): mixed
            {
                return 'path';
            }

            public function getQueryParameter(string $key, mixed $default = null): mixed
            {
                return '1';
            }

            public function hasQueryParameter(string $key): bool
            {
                return $key === 'value';
            }

            public function getAllQueryParameters(): array
            {
                return ['value' => '1'];
            }

            public function withQueryParameter(string $key, string $value): static
            {
                return $this;
            }

            public function withQueryParameters(array $parameters): static
            {
                return $this;
            }

            public function withoutQueryParameter(string $key): static
            {
                return $this;
            }

            public function withoutQueryParameters(): static
            {
                return $this;
            }
        };

        $wrapped = $this->factory->make($foreignUrl);

        $this->assertInstanceOf(Url::class, $wrapped);
        $this->assertNotSame($foreignUrl, $wrapped);
        $this->assertSame('https://example.com/path?value=1', (string)$wrapped);
    }

    public function test_from_string_scrubs_percent_encoded_invalid_utf8_in_query(): void
    {
        $url = $this->factory->make('https://example.com/?q=%FF');
        $json = json_encode($url->getAllQueryParameters());

        $this->assertNotFalse($json, 'json_encode failed: ' . json_last_error_msg());
    }

    public function test_get_base_keeps_port(): void
    {
        $url = Url::fromString('https://example.com:8443/path');

        $this->assertSame('https://example.com:8443', $url->getBase());
    }
}
