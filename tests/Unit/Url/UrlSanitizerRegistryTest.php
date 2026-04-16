<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Sanitizers\ControlCharsSanitizer;
use Atldays\Url\Sanitizers\HeaderValueSanitizer;
use Atldays\Url\Sanitizers\Utf8Sanitizer;
use Atldays\Url\UrlSanitizerRegistry;
use InvalidArgumentException;

final class UrlSanitizerRegistryTest extends TestCase
{
    public function test_it_returns_the_default_profile_name_from_config(): void
    {
        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $this->assertSame('default', $registry->getDefaultProfile());
    }

    public function test_it_resolves_sanitizers_for_the_default_profile_in_order(): void
    {
        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $sanitizers = $registry->forProfile('default');

        $this->assertCount(2, $sanitizers);
        $this->assertInstanceOf(ControlCharsSanitizer::class, $sanitizers[0]);
        $this->assertInstanceOf(Utf8Sanitizer::class, $sanitizers[1]);
    }

    public function test_it_resolves_sanitizers_for_the_header_profile_in_order(): void
    {
        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $sanitizers = $registry->forProfile('header');

        $this->assertCount(3, $sanitizers);
        $this->assertInstanceOf(HeaderValueSanitizer::class, $sanitizers[0]);
        $this->assertInstanceOf(ControlCharsSanitizer::class, $sanitizers[1]);
        $this->assertInstanceOf(Utf8Sanitizer::class, $sanitizers[2]);
    }

    public function test_it_throws_for_unknown_profiles(): void
    {
        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $this->expectException(InvalidArgumentException::class);

        $registry->forProfile('missing-profile');
    }

    public function test_it_throws_for_invalid_sanitizer_configuration(): void
    {
        config()->set('url.profiles.invalid', ['']);

        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $this->expectException(InvalidArgumentException::class);

        $registry->forProfile('invalid');
    }

    public function test_it_throws_when_configured_class_does_not_implement_the_interface(): void
    {
        config()->set('url.profiles.invalid_class', [\stdClass::class]);

        $registry = $this->app->make(UrlSanitizerRegistry::class);

        $this->expectException(InvalidArgumentException::class);

        $registry->forProfile('invalid_class');
    }
}
