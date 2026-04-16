<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Data\Transformers\UrlTransformer;
use Atldays\Url\Url;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

final class UrlTransformerTest extends TestCase
{
    public function test_it_transforms_url_instances_to_strings(): void
    {
        $data = UrlTransformerData::from([
            'url' => Url::fromString('https://example.com/path?value=1'),
        ]);

        $this->assertSame([
            'url' => 'https://example.com/path?value=1',
        ], $data->toArray());
    }

    public function test_it_transforms_nullable_urls_to_null(): void
    {
        $data = NullableUrlTransformerData::from([
            'url' => null,
        ]);

        $this->assertSame([
            'url' => null,
        ], $data->toArray());
    }
}

final class UrlTransformerData extends Data
{
    public function __construct(
        #[WithTransformer(UrlTransformer::class)]
        public Url $url,
    ) {}
}

final class NullableUrlTransformerData extends Data
{
    public function __construct(
        #[WithTransformer(UrlTransformer::class)]
        public ?Url $url,
    ) {}
}
