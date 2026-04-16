<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Data\Casts\ToUrlCast;
use Atldays\Url\Url;
use InvalidArgumentException;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class ToUrlCastTest extends TestCase
{
    public function test_it_keeps_existing_url_instances(): void
    {
        $existing = Url::fromString('https://example.com/path');

        $data = ToUrlCastData::from([
            'url' => $existing,
        ]);

        $this->assertInstanceOf(Url::class, $data->url);
        $this->assertSame('https://example.com/path', (string)$data->url);
    }

    public function test_it_casts_http_urls_as_is(): void
    {
        $data = ToUrlCastData::from([
            'url' => 'https://example.com/path?value=1',
        ]);

        $this->assertSame('https://example.com/path?value=1', (string)$data->url);
    }

    public function test_it_casts_host_and_path_to_https_url(): void
    {
        $data = ToUrlCastData::from([
            'url' => 'example.com/path?value=1',
        ]);

        $this->assertSame('https://example.com/path?value=1', (string)$data->url);
    }

    public function test_it_casts_a_plain_host_to_https_url(): void
    {
        $data = ToUrlCastData::from([
            'url' => 'example.com',
        ]);

        $this->assertSame('https://example.com', (string)$data->url);
    }

    public function test_it_returns_null_for_invalid_values_when_nullable_and_not_strict(): void
    {
        $data = NullableToUrlCastData::from([
            'url' => 'not-a-host',
        ]);

        $this->assertNull($data->url);
    }

    public function test_it_throws_in_strict_mode_for_invalid_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StrictToUrlCastData::from([
            'url' => 'not-a-host',
        ]);
    }
}

final class ToUrlCastData extends Data
{
    public function __construct(
        #[WithCast(ToUrlCast::class)]
        public Url $url,
    ) {}
}

final class NullableToUrlCastData extends Data
{
    public function __construct(
        #[WithCast(ToUrlCast::class)]
        public ?Url $url,
    ) {}
}

final class StrictToUrlCastData extends Data
{
    public function __construct(
        #[WithCast(ToUrlCast::class, true)]
        public Url $url,
    ) {}
}
