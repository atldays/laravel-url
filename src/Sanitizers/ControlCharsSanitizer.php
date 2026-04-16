<?php

declare(strict_types=1);

namespace Atldays\Url\Sanitizers;

use Atldays\Url\SanitizedUrlInput;

final class ControlCharsSanitizer implements UrlSanitizer
{
    public function sanitize(SanitizedUrlInput $input): SanitizedUrlInput
    {
        $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', '', $input->current) ?? $input->current;

        return $input->withCurrent($sanitized, 'control_chars');
    }
}
