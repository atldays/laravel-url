<?php

declare(strict_types=1);

namespace Atldays\Url\Contracts;

interface Query
{
    public function getQueryParameter(string $key, mixed $default = null): mixed;

    public function hasQueryParameter(string $key): bool;

    public function getAllQueryParameters(): array;

    public function withQueryParameter(string $key, string $value): static;

    public function withQueryParameters(array $parameters): static;

    public function withoutQueryParameter(string $key): static;

    public function withoutQueryParameters(): static;
}
