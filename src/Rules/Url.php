<?php

declare(strict_types=1);

namespace Atldays\Url\Rules;

use Atldays\Url\UrlFactory;
use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Throwable;

use function Atldays\Url\is_url;

readonly class Url implements ValidationRule
{
    /**
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            if (!is_string($value)) {
                throw new Exception('Invalid URL.');
            }

            $url = app(UrlFactory::class)->make($value);

            if ($url->isBrowserSpecific()) {
                return;
            }

            if (!is_url($value)) {
                throw new Exception('Invalid URL.');
            }
        } catch (Throwable) {
            $fail('url::validation.url')->translate();
        }
    }
}
