<?php

declare(strict_types=1);

namespace Atldays\Url\Tests;

use Atldays\Url\Url;
use PHPUnit\Framework\TestCase;

final class BrowserSpecificUrlTest extends TestCase
{
    public function test_browser_extension_url_is_browser_specific(): void
    {
        $url = Url::fromString('chrome-extension://amenebmoegbfiohcnmoiaheccgikmfid/html/player.html?media_id=ae5df09f1d886a18813a57c1f9ce6337d7ffd5ac592473115902837548c7acc6&tab_id=1031309877&chromecast=1');

        $this->assertTrue($url->hasBrowserSpecificScheme());
        $this->assertTrue($url->isBrowserSpecific());
    }

    public function test_browser_internal_url_is_browser_specific(): void
    {
        $url = Url::fromString('chrome://settings');

        $this->assertTrue($url->hasBrowserSpecificScheme());
        $this->assertTrue($url->isBrowserSpecific());
    }

    public function test_firefox_extension_url_is_browser_specific(): void
    {
        $url = Url::fromString('moz-extension://a243761d-19d7-4087-a436-e454657d0a5a');

        $this->assertTrue($url->hasBrowserSpecificScheme());
        $this->assertTrue($url->isBrowserSpecific());
    }

    public function test_non_browser_specific_url_is_not_browser_specific(): void
    {
        $url = Url::fromString('https://www.youtube.com/watch?v=9bZkp7q19f0');

        $this->assertFalse($url->hasBrowserSpecificScheme());
        $this->assertFalse($url->isBrowserSpecific());
    }
}
