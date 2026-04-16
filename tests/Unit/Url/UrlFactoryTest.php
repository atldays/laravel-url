<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Contracts\Url as UrlContract;
use Atldays\Url\Url;
use Atldays\Url\UrlFactory;
use InvalidArgumentException;

final class UrlFactoryTest extends TestCase
{
    public function test_it_uses_the_default_profile_when_none_is_provided(): void
    {
        $factory = $this->app->make(UrlFactory::class);

        $url = $factory->make("https://example.com/\npath");

        $this->assertSame('https://example.com/path', (string)$url);
    }

    public function test_it_uses_the_explicit_header_profile(): void
    {
        $factory = $this->app->make(UrlFactory::class);

        $url = $factory->make(' "https://example.com/path?x=1" ', 'header');

        $this->assertSame('https://example.com/path?x=1', (string)$url);
    }

    public function test_it_returns_the_same_instance_when_our_url_object_is_passed(): void
    {
        $factory = $this->app->make(UrlFactory::class);
        $url = Url::fromString('https://example.com/path');

        $result = $factory->make($url);

        $this->assertSame($url, $result);
    }

    public function test_it_rewraps_foreign_url_contract_implementations(): void
    {
        $factory = $this->app->make(UrlFactory::class);
        $foreignUrl = new class implements UrlContract
        {
            public function __toString(): string
            {
                return 'https://example.com/path?value=1';
            }

            public function isIpHost(): bool
            {
                return false;
            }

            public function hasBrowserScheme(): bool
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

        $wrapped = $factory->make($foreignUrl);

        $this->assertInstanceOf(Url::class, $wrapped);
        $this->assertNotSame($foreignUrl, $wrapped);
        $this->assertSame('https://example.com/path?value=1', (string)$wrapped);
    }

    public function test_it_returns_null_for_invalid_values_in_make_or_null(): void
    {
        $factory = $this->app->make(UrlFactory::class);

        $this->assertNull($factory->makeOrNull('schema://example.com'));
        $this->assertNull($factory->makeOrNull(null));
    }

    public function test_it_throws_for_unknown_profiles(): void
    {
        $factory = $this->app->make(UrlFactory::class);

        $this->expectException(InvalidArgumentException::class);

        $factory->make('https://example.com', 'missing-profile');
    }
}
