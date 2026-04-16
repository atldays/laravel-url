<?php

declare(strict_types=1);

namespace Atldays\Url;

use Atldays\Url\Contracts\Url as UrlContract;
use Illuminate\Http\Request;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class UrlServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-url')
            ->hasConfigFile()
            ->hasTranslations();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(UrlSanitizerRegistry::class);
        $this->app->singleton(UrlFactory::class);
    }

    public function packageBooted(): void
    {
        $this->registerRequestMacros();
    }

    protected function registerRequestMacros(): void
    {
        Request::macro('getUrlFromHeader', function (string $header, ?UrlContract $default = null): ?UrlContract {
            /** @var Request $this */
            $value = $this->header($header);
            $factory = app(UrlFactory::class);

            if (is_string($value) && is_url($value)) {
                $url = $factory->makeOrNull($value, 'header');

                if ($url !== null && !$url->isIpHost()) {
                    return $url;
                }
            }

            return $default;
        });

        Request::macro('getOriginUrl', function (?UrlContract $default = null): ?UrlContract {
            /** @var Request $this */
            return $this->getUrlFromHeader('origin', $default);
        });

        Request::macro('getRefererUrl', function (?UrlContract $default = null): ?UrlContract {
            /** @var Request $this */
            return $this->getUrlFromHeader('referer', $default);
        });

        Request::macro('getFullUrl', function (): UrlContract {
            /** @var Request $this */
            return app(UrlFactory::class)->make($this->fullUrl());
        });
    }
}
