<?php

declare(strict_types=1);

namespace Atldays\Url\Rules;

use Atldays\Url\Facades\Url as UrlFacade;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Spatie\Url\Exceptions\InvalidArgument;

readonly class Url implements ValidationRule
{
    /**
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value === null) {
            return;
        }

        if (!is_string($value)) {
            $fail('url::validation.url')->translate();

            return;
        }

        try {
            $url = UrlFacade::make($value);
        } catch (InvalidArgument) {
            $fail('url::validation.url')->translate();

            return;
        }

        if ($url->hasBrowserScheme()) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            $fail('url::validation.url')->translate();
        }
    }
}
