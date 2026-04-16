<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Url;
use PHPUnit\Framework\TestCase;
use Spatie\Url\Exceptions\InvalidArgument;
use Spatie\Url\Scheme;

final class SchemeTest extends TestCase
{
    public function test_can_be_instantiated(): void
    {
        $scheme = new Scheme(allowedSchemes: Url::getValidSchemes());

        $this->assertSame('', $scheme->getScheme());
        $this->assertSame(Url::getValidSchemes(), $scheme->getAllowedSchemes());
    }

    public function test_casts_to_string(): void
    {
        $scheme = new Scheme;

        $scheme->setAllowedSchemes(['ws', 'wss']);
        $scheme->setScheme('wss');

        $this->assertSame('wss', (string)$scheme);
    }

    public function test_sanitizes_the_scheme(): void
    {
        $scheme = new Scheme;

        $scheme->setScheme('HTTPS');

        $this->assertSame('https', $scheme->getScheme());
    }

    public function test_validates_by_default_allowed_schemes_when_setting_the_scheme(): void
    {
        $scheme = new Scheme;

        $scheme->setScheme('https');

        $this->assertSame('https', $scheme->getScheme());
    }

    public function test_rejects_invalid_scheme_for_url_allowed_schemes(): void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage(InvalidArgument::invalidScheme('xss', Url::getValidSchemes())->getMessage());

        $scheme = new Scheme(allowedSchemes: Url::getValidSchemes());
        $scheme->setScheme('xss');
    }

    public function test_validates_by_custom_allowed_schemes_when_setting_the_scheme(): void
    {
        $scheme = new Scheme;

        $scheme->setAllowedSchemes(['ws', 'wss']);
        $scheme->setScheme('wss');

        $this->assertSame('wss', $scheme->getScheme());
    }

    public function test_rejects_scheme_not_in_custom_allowed_list(): void
    {
        $this->expectException(InvalidArgument::class);
        $this->expectExceptionMessage(InvalidArgument::invalidScheme('https', ['ws', 'wss'])->getMessage());

        $scheme = new Scheme;
        $scheme->setAllowedSchemes(['ws', 'wss']);
        $scheme->setScheme('https');
    }
}
