<?php

declare(strict_types=1);

namespace Atldays\Url\Contracts;

use Psr\Http\Message\UriInterface;

interface Url extends Query, Segment, UriInterface
{
    public function isIp(): bool;

    public function hasBrowserSpecificScheme(): bool;

    public function isBrowserSpecific(): bool;

    public function getBase(): string;
}
