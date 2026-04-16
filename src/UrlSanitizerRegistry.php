<?php

declare(strict_types=1);

namespace Atldays\Url;

use Atldays\Url\Sanitizers\UrlSanitizer;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Container\Container;
use InvalidArgumentException;

final readonly class UrlSanitizerRegistry
{
    public function __construct(
        private Config $config,
        private Container $container,
    ) {}

    public function getDefaultProfile(): string
    {
        return (string)$this->config->get('url.default_profile', 'default');
    }

    /**
     * @return list<UrlSanitizer>
     */
    public function forProfile(string $profile): array
    {
        $sanitizers = $this->config->get("url.profiles.{$profile}");

        if (!is_array($sanitizers)) {
            throw new InvalidArgumentException("Unknown URL sanitizer profile [{$profile}].");
        }

        return array_map(
            fn (mixed $sanitizer): UrlSanitizer => $this->resolve($sanitizer, $profile),
            $sanitizers,
        );
    }

    private function resolve(mixed $sanitizer, string $profile): UrlSanitizer
    {
        if (!is_string($sanitizer) || $sanitizer === '') {
            throw new InvalidArgumentException("Invalid URL sanitizer configured for profile [{$profile}].");
        }

        $resolved = $this->container->make($sanitizer);

        if (!$resolved instanceof UrlSanitizer) {
            throw new InvalidArgumentException("Configured URL sanitizer [{$sanitizer}] must implement UrlSanitizer.");
        }

        return $resolved;
    }
}
