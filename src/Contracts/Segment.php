<?php

declare(strict_types=1);

namespace Atldays\Url\Contracts;

interface Segment
{
    public function getSegments(): array;

    public function getSegment(int $index, mixed $default = null): mixed;

    public function getFirstSegment(): mixed;

    public function getLastSegment(): mixed;
}
