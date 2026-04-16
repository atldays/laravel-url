<?php

declare(strict_types=1);

namespace Atldays\Url\Data\Transformers;

use Atldays\Url\Contracts\Url as UrlContract;
use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Support\Transformation\TransformationContext;
use Spatie\LaravelData\Transformers\Transformer;

final class UrlTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value, TransformationContext $context): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof UrlContract) {
            return null;
        }

        return (string)$value;
    }
}
