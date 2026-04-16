<?php

declare(strict_types=1);

namespace Atldays\Url;

use Illuminate\Support\Str;

if (!function_exists(__NAMESPACE__ . '\\is_url')) {
    function is_url(?string $url): bool
    {
        if (!is_string($url)) {
            return false;
        }

        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}

if (!function_exists(__NAMESPACE__ . '\\is_valid_host')) {
    function is_valid_host(mixed $host): bool
    {
        if (
            !is_string($host)
            || Str::startsWith($host, '.')
            || Str::endsWith($host, '.')
            || !Str::contains($host, '.')
        ) {
            return false;
        }

        return filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false;
    }
}
