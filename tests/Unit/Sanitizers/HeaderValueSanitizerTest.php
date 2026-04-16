<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\SanitizedUrlInput;
use Atldays\Url\Sanitizers\HeaderValueSanitizer;

final class HeaderValueSanitizerTest extends TestCase
{
    public function test_it_trims_and_unwraps_header_values(): void
    {
        $sanitizer = new HeaderValueSanitizer;
        $input = new SanitizedUrlInput(
            original: '  "https://example.com/path?x=1"  ',
            current: '  "https://example.com/path?x=1"  ',
            profile: 'header',
        );

        $sanitized = $sanitizer->sanitize($input);

        $this->assertSame('https://example.com/path?x=1', $sanitized->current);
    }
}
