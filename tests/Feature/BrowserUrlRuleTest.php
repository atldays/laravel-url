<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Rules\BrowserUrl;
use Illuminate\Support\Facades\Validator;

final class BrowserUrlRuleTest extends TestCase
{
    public function test_rule_accepts_browser_scheme_urls(): void
    {
        $validator = Validator::make(
            ['url' => 'chrome-extension://amenebmoegbfiohcnmoiaheccgikmfid/html/player.html'],
            ['url' => [new BrowserUrl]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_rule_rejects_regular_urls(): void
    {
        $validator = Validator::make(
            ['url' => 'https://example.com'],
            ['url' => [new BrowserUrl]],
        );

        $this->assertFalse($validator->passes());
        $this->assertSame(
            'The url must be a valid browser URL.',
            $validator->errors()->first('url'),
        );
    }

    public function test_rule_allows_null_when_used_with_nullable(): void
    {
        $validator = Validator::make(
            ['url' => null],
            ['url' => ['nullable', new BrowserUrl]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_rule_rejects_non_string_values(): void
    {
        $validator = Validator::make(
            ['url' => 123],
            ['url' => [new BrowserUrl]],
        );

        $this->assertFalse($validator->passes());
        $this->assertSame(
            'The url must be a valid browser URL.',
            $validator->errors()->first('url'),
        );
    }
}
