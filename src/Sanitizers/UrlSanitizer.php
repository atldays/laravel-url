<?php

declare(strict_types=1);

namespace Atldays\Url\Sanitizers;

use Atldays\Url\SanitizedUrlInput;

interface UrlSanitizer
{
    public function sanitize(SanitizedUrlInput $input): SanitizedUrlInput;
}
