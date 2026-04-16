<?php

declare(strict_types=1);

namespace Atldays\Url\Data\Casts;

use Atldays\Url\Contracts\Url as UrlContract;
use Atldays\Url\Facades\Url as UrlFacade;
use Atldays\Url\Url;
use InvalidArgumentException;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

final class ToUrlCast implements Cast
{
    public function __construct(
        protected bool $strict = false,
        protected ?string $profile = null,
    ) {}

    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): ?UrlContract
    {
        if ($value instanceof UrlContract) {
            return UrlFacade::make((string)$value, $this->profile);
        }

        if (is_string($value)) {
            $url = $this->castStringValue($value);

            if ($url !== null) {
                return $url;
            }
        }

        if (!$this->isValidHost($value)) {
            if (!$this->strict && $property->type->isNullable) {
                return null;
            }

            throw new InvalidArgumentException("Cannot cast [{$property->name}] to URL from the given value.");
        }

        return Url::create()
            ->withHost($value)
            ->withScheme('https');
    }

    private function castStringValue(string $value): ?UrlContract
    {
        $url = null;

        try {
            if (str_starts_with($value, 'http')) {
                $url = UrlFacade::make($value, $this->profile);
            } elseif (str_contains($value, '/')) {
                $url = UrlFacade::make('https://' . $value, $this->profile);
            }
        } catch (InvalidArgumentException) {
            return null;
        }

        if ($url !== null && $this->isValidHost($url->getHost())) {
            return $url;
        }

        return null;
    }

    private function isValidHost(mixed $host): bool
    {
        if (
            !is_string($host)
            || $host === ''
            || str_starts_with($host, '.')
            || str_ends_with($host, '.')
            || !str_contains($host, '.')
        ) {
            return false;
        }

        return filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
    }
}
