<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Data\Casts\UrlCast;
use Atldays\Url\Url;
use InvalidArgumentException;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;

final class UrlCastTest extends TestCase
{
    public function test_it_casts_a_string_to_a_url_instance(): void
    {
        $data = UrlCastData::from([
            'url' => 'https://example.com/path?value=1',
        ]);

        $this->assertInstanceOf(Url::class, $data->url);
        $this->assertSame('https://example.com/path?value=1', (string)$data->url);
    }

    public function test_it_keeps_null_for_nullable_properties(): void
    {
        $data = NullableUrlCastData::from([
            'url' => null,
        ]);

        $this->assertNull($data->url);
    }

    public function test_it_throws_in_strict_mode_for_invalid_values(): void
    {
        $this->expectException(InvalidArgumentException::class);

        StrictUrlCastData::from([
            'url' => 'not-a-url',
        ]);
    }
}

final class UrlCastData extends Data
{
    public function __construct(
        #[WithCast(UrlCast::class)]
        public Url $url,
    ) {}
}

final class NullableUrlCastData extends Data
{
    public function __construct(
        #[WithCast(UrlCast::class)]
        public ?Url $url,
    ) {}
}

final class StrictUrlCastData extends Data
{
    public function __construct(
        #[WithCast(UrlCast::class, true)]
        public Url $url,
    ) {}
}
