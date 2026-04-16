<?php

declare(strict_types=1);

namespace Atldays\Url\Sanitizers;

use Atldays\Url\SanitizedUrlInput;

final class HeaderValueSanitizer implements UrlSanitizer
{
    public function sanitize(SanitizedUrlInput $input): SanitizedUrlInput
    {
        $sanitized = trim($input->current);
        $sanitized = trim($sanitized, "\"' \t");
        $sanitized = preg_replace('/\s+/u', '', $sanitized) ?? $sanitized;

        return $input->withCurrent($sanitized, 'header_value');
    }
}
