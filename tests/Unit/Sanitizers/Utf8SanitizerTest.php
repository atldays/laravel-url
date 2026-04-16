<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\SanitizedUrlInput;
use Atldays\Url\Sanitizers\Utf8Sanitizer;

final class Utf8SanitizerTest extends TestCase
{
    private Utf8Sanitizer $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sanitizer = new Utf8Sanitizer;
    }

    public function test_it_keeps_valid_utf8_untouched(): void
    {
        $sanitized = $this->sanitize('https://example.com/?q=test');

        $this->assertSame('https://example.com?q=test', $sanitized->current);
        $this->assertSame(['utf8'], $sanitized->changes);
    }

    public function test_it_scrubs_invalid_utf8_in_path(): void
    {
        $sanitized = $this->sanitize("https://example.com/ab\xC3\x28");

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
        $this->assertStringStartsWith('https://example.com/ab', $sanitized->current);
        $this->assertSame(['utf8'], $sanitized->changes);
    }

    public function test_it_scrubs_invalid_utf8_in_query(): void
    {
        $sanitized = $this->sanitize('https://example.com/?q=%FF');

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
        $this->assertStringStartsWith('https://example.com?q=', $sanitized->current);
        $this->assertSame(['utf8'], $sanitized->changes);
    }

    public function test_it_scrubs_invalid_utf8_in_fragment(): void
    {
        $sanitized = $this->sanitize("https://example.com/path#fr\xC3\x28");

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
        $this->assertStringStartsWith('https://example.com/path#fr', $sanitized->current);
    }

    public function test_it_scrubs_invalid_utf8_in_user_info(): void
    {
        $sanitized = $this->sanitize("https://us\xC3\x28:pa\xC3\x28@example.com/path");

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
        $this->assertNotSame('', $sanitized->current);
    }

    public function test_it_scrubs_invalid_utf8_in_nested_query_arrays(): void
    {
        $sanitized = $this->sanitize('https://example.com/?filters[name]=%FF&filters[level]=ok');

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
        $this->assertStringContainsString('filters%5Bname%5D=', $sanitized->current);
        $this->assertStringContainsString('filters%5Blevel%5D=ok', $sanitized->current);
    }

    public function test_it_preserves_mailto_urls(): void
    {
        $sanitized = $this->sanitize('mailto:test@example.com');

        $this->assertSame('mailto:test@example.com', $sanitized->current);
    }

    public function test_it_preserves_tel_urls(): void
    {
        $sanitized = $this->sanitize('tel:+380001112233');

        $this->assertSame('tel:+380001112233', $sanitized->current);
    }

    public function test_it_preserves_schemeless_urls(): void
    {
        $sanitized = $this->sanitize('//example.com/path?q=1');

        $this->assertSame('//example.com/path?q=1', $sanitized->current);
    }

    public function test_it_preserves_ports(): void
    {
        $sanitized = $this->sanitize('https://example.com:8443/path?q=1');

        $this->assertSame('https://example.com:8443/path?q=1', $sanitized->current);
    }

    public function test_it_returns_valid_utf8_for_completely_invalid_input(): void
    {
        $sanitized = $this->sanitize("\xC3");

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
    }

    public function test_it_returns_valid_utf8_for_strings_that_are_not_final_urls_yet(): void
    {
        $sanitized = $this->sanitize('https://exa mple.com');

        $this->assertTrue(mb_check_encoding($sanitized->current, 'UTF-8'));
    }

    private function sanitize(string $url): SanitizedUrlInput
    {
        return $this->sanitizer->sanitize(
            new SanitizedUrlInput(
                original: $url,
                current: $url,
                profile: 'default',
            ),
        );
    }
}
