<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\SanitizedUrlInput;
use Atldays\Url\Sanitizers\ControlCharsSanitizer;

final class ControlCharsSanitizerTest extends TestCase
{
    public function test_it_removes_control_characters(): void
    {
        $sanitizer = new ControlCharsSanitizer;
        $input = new SanitizedUrlInput(
            original: "https://example.com/\npath\t?q=1",
            current: "https://example.com/\npath\t?q=1",
            profile: 'default',
        );

        $sanitized = $sanitizer->sanitize($input);

        $this->assertSame('https://example.com/path?q=1', $sanitized->current);
    }
}
