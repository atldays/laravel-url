<?php

declare(strict_types=1);

namespace Atldays\Url\Facades;

use Atldays\Url\UrlFactory;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Atldays\Url\Url make(string|\Atldays\Url\Contracts\Url $input, ?string $profile = null)
 * @method static ?\Atldays\Url\Url makeOrNull(null|string|\Atldays\Url\Contracts\Url $input, ?string $profile = null)
 *
 * @see UrlFactory
 */
final class Url extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UrlFactory::class;
    }
}
