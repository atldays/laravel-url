<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Rules\Url;
use Illuminate\Support\Facades\Validator;

final class RuleTest extends TestCase
{
    public function test_rule_accepts_browser_specific_scheme_urls(): void
    {
        $validator = Validator::make(
            ['url' => 'chrome-extension://amenebmoegbfiohcnmoiaheccgikmfid/html/player.html'],
            ['url' => [new Url]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_rule_uses_package_translation_message(): void
    {
        $validator = Validator::make(
            ['url' => 'not-a-url'],
            ['url' => [new Url]],
        );

        $this->assertFalse($validator->passes());
        $this->assertSame('The url must be a valid URL.', $validator->errors()->first('url'));
    }

    public function test_rule_allows_null_when_used_with_nullable(): void
    {
        $validator = Validator::make(
            ['url' => null],
            ['url' => ['nullable', new Url]],
        );

        $this->assertTrue($validator->passes());
    }

    public function test_rule_rejects_non_string_values(): void
    {
        $validator = Validator::make(
            ['url' => 123],
            ['url' => [new Url]],
        );

        $this->assertFalse($validator->passes());
        $this->assertSame('The url must be a valid URL.', $validator->errors()->first('url'));
    }
}
