<?php

declare(strict_types=1);

namespace Atldays\Url\Data\Casts;

use Atldays\Url\Contracts\Url as UrlContract;
use Atldays\Url\Facades\Url as UrlFacade;
use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Casts\Uncastable;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class UrlCast implements Cast
{
    public function __construct(
        protected bool $strict = false,
        protected ?string $profile = null,
    ) {}

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): UrlContract|Uncastable|null
    {
        if ($value === null) {
            return $property->type->isNullable ? null : Uncastable::create();
        }

        if ($value instanceof UrlContract) {
            return UrlFacade::make((string)$value, $this->profile);
        }

        if (!is_string($value)) {
            return $this->strict
                ? throw new InvalidArgumentException("Cannot cast [{$property->name}] to URL from a non-string value.")
                : Uncastable::create();
        }

        $url = UrlFacade::makeOrNull($value, $this->profile);

        if ($url !== null && ($url->hasBrowserScheme() || filter_var($value, FILTER_VALIDATE_URL) !== false)) {
            return $url;
        }

        if ($this->strict) {
            throw new InvalidArgumentException("Cannot cast [{$property->name}] to URL from the given value.");
        }

        return Uncastable::create();
    }
}
