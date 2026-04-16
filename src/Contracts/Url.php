<?php

declare(strict_types=1);

namespace Atldays\Url\Contracts;

use Psr\Http\Message\UriInterface;

interface Url extends Query, Segment, UriInterface
{
    public function isIpHost(): bool;

    public function hasBrowserScheme(): bool;

    public function getBase(): string;
}
