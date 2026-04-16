<?php

declare(strict_types=1);

namespace Atldays\Url;

use Atldays\Url\Contracts\Url as UrlContract;
use Spatie\Url\SchemeValidator;
use Spatie\Url\Url as BaseUrl;

class Url extends BaseUrl implements UrlContract
{
    public const BROWSER_SCHEMES = ['chrome-extension', 'moz-extension', 'chrome', 'opera', 'edge'];

    public static function getValidSchemes(): array
    {
        return array_merge(SchemeValidator::VALID_SCHEMES, static::BROWSER_SCHEMES);
    }

    public function __construct()
    {
        parent::__construct();

        $this->scheme->setAllowedSchemes(static::getValidSchemes());
    }

    public function isIpHost(): bool
    {
        return filter_var($this->getHost(), FILTER_VALIDATE_IP) !== false;
    }

    public function hasBrowserScheme(): bool
    {
        return in_array($this->getScheme(), static::BROWSER_SCHEMES, true);
    }

    public function getBase(): string
    {
        $base = $this->getScheme() . '://' . $this->getHost();

        if ($this->getPort() !== null) {
            $base .= ':' . $this->getPort();
        }

        return $base;
    }
}
