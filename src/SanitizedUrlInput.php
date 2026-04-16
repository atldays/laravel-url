<?php

declare(strict_types=1);

namespace Atldays\Url;

final readonly class SanitizedUrlInput
{
    /**
     * @param list<string> $changes
     */
    public function __construct(
        public string $original,
        public string $current,
        public string $profile,
        public array $changes = [],
    ) {}

    public function withCurrent(string $current, ?string $change = null): self
    {
        $changes = $this->changes;

        if ($change !== null && $current !== $this->current) {
            $changes[] = $change;
        }

        return new self(
            original: $this->original,
            current: $current,
            profile: $this->profile,
            changes: $changes,
        );
    }
}
