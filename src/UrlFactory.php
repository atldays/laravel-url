<?php

declare(strict_types=1);

namespace Atldays\Url;

use Atldays\Url\Contracts\Url as UrlContract;
use Spatie\Url\Exceptions\InvalidArgument;

final readonly class UrlFactory
{
    public function __construct(
        private UrlSanitizerRegistry $registry,
    ) {}

    public function make(string|UrlContract $input, ?string $profile = null): Url
    {
        if ($input instanceof Url) {
            return $input;
        }

        $profile ??= $this->registry->getDefaultProfile();
        $state = new SanitizedUrlInput(
            original: (string)$input,
            current: (string)$input,
            profile: $profile,
        );

        foreach ($this->registry->forProfile($profile) as $sanitizer) {
            $state = $sanitizer->sanitize($state);
        }

        return Url::fromString($state->current);
    }

    public function makeOrNull(null|string|UrlContract $input, ?string $profile = null): ?Url
    {
        if ($input === null) {
            return null;
        }

        try {
            return $this->make($input, $profile);
        } catch (InvalidArgument) {
            return null;
        }
    }
}
