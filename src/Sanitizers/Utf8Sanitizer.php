<?php

declare(strict_types=1);

namespace Atldays\Url\Sanitizers;

use Atldays\Url\SanitizedUrlInput;
use Spatie\Url\Exceptions\InvalidArgument;

final class Utf8Sanitizer implements UrlSanitizer
{
    private const SCALAR_PARTS = ['host', 'path', 'user', 'pass', 'fragment'];

    private const OPAQUE_SCHEMES = ['mailto', 'tel'];

    public function sanitize(SanitizedUrlInput $input): SanitizedUrlInput
    {
        return $input->withCurrent(
            $this->sanitizeUrlString($input->current),
            'utf8',
        );
    }

    private function sanitizeUrlString(string $url): string
    {
        $scrubbedUrl = $this->scrubStringOrFail($url);
        $parts = $this->parseUrl($scrubbedUrl);
        $parts = $this->sanitizeParsedParts($parts);

        return $this->buildUrl($parts);
    }

    /**
     * @return array<string, mixed>
     */
    private function parseUrl(string $url): array
    {
        $parts = parse_url($url);

        if ($parts === false) {
            throw InvalidArgument::invalidUrl($url);
        }

        return $parts;
    }

    /**
     * @param array<string, mixed> $parts
     * @return array<string, mixed>
     */
    private function sanitizeParsedParts(array $parts): array
    {
        foreach (self::SCALAR_PARTS as $part) {
            if (isset($parts[$part]) && is_string($parts[$part])) {
                $parts[$part] = $this->sanitizeScalarPart($parts[$part]);
            }
        }

        if (isset($parts['query']) && is_string($parts['query'])) {
            $parts['query'] = $this->sanitizeQuery($parts['query']);
        }

        return $parts;
    }

    private function sanitizeScalarPart(string $value): string
    {
        return $this->scrubStringOrNull($value) ?? '';
    }

    private function sanitizeQuery(string $query): string
    {
        $parameters = [];

        parse_str($query, $parameters);

        return http_build_query($this->scrubArray($parameters), '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * @param array<string, mixed> $parts
     */
    private function buildUrl(array $parts): string
    {
        $scheme = (string)($parts['scheme'] ?? '');
        $host = (string)($parts['host'] ?? '');
        $port = $parts['port'] ?? null;
        $user = (string)($parts['user'] ?? '');
        $password = $parts['pass'] ?? null;
        $path = (string)($parts['path'] ?? '/');
        $query = (string)($parts['query'] ?? '');
        $fragment = (string)($parts['fragment'] ?? '');

        $authority = $this->buildAuthority($host, $port, $user, $password);
        $url = $this->buildSchemePrefix($scheme, $authority, $path);

        if ($authority !== '') {
            $url .= $authority;
        }

        if ($path !== '/') {
            $url .= $this->normalizePathForScheme($scheme, $path);
        }

        if ($query !== '') {
            $url .= '?' . $query;
        }

        if ($fragment !== '') {
            $url .= '#' . $fragment;
        }

        return $url;
    }

    private function buildAuthority(string $host, mixed $port, string $user, mixed $password): string
    {
        $authority = $host;

        if ($user !== '') {
            $authority = $user;

            if (is_string($password) && $password !== '') {
                $authority .= ':' . $password;
            }

            $authority .= '@' . $host;
        }

        if (is_int($port)) {
            $authority .= ':' . $port;
        }

        return $authority;
    }

    private function buildSchemePrefix(string $scheme, string $authority, string $path): string
    {
        if ($scheme !== '' && !$this->isOpaqueScheme($scheme)) {
            return $scheme . '://';
        }

        if ($this->isOpaqueScheme($scheme) && $path !== '') {
            return $scheme . ':';
        }

        if ($scheme === '' && $authority !== '') {
            return '//';
        }

        return '';
    }

    private function normalizePathForScheme(string $scheme, string $path): string
    {
        return $this->isOpaqueScheme($scheme)
            ? ltrim($path, '/')
            : $path;
    }

    private function isOpaqueScheme(string $scheme): bool
    {
        return in_array($scheme, self::OPAQUE_SCHEMES, true);
    }

    private function scrubStringOrFail(string $value): string
    {
        $sanitized = $this->scrubStringOrNull($value);

        if ($sanitized === null) {
            throw InvalidArgument::invalidUrl('[invalid-utf8]');
        }

        return $sanitized;
    }

    private function scrubStringOrNull(string $value): ?string
    {
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        if (function_exists('mb_scrub')) {
            $value = mb_scrub($value, 'UTF-8');
        } else {
            $scrubbed = @iconv('UTF-8', 'UTF-8//IGNORE', $value);
            $value = $scrubbed === false ? '' : $scrubbed;
        }

        if ($value === '' || !mb_check_encoding($value, 'UTF-8')) {
            return null;
        }

        return $value;
    }

    private function scrubArray(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if (is_string($key)) {
                $key = $this->scrubStringOrNull($key) ?? '';
            }

            if (is_array($value)) {
                $result[$key] = $this->scrubArray($value);

                continue;
            }

            if (is_string($value)) {
                $result[$key] = $this->scrubStringOrNull($value) ?? '';

                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
