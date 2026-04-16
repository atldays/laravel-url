<?php

declare(strict_types=1);

namespace Atldays\Url;

use Atldays\Url\Contracts\Url as UrlContract;
use Illuminate\Contracts\Container\BindingResolutionException;
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

    /**
     * @throws BindingResolutionException
     */
    protected function registerRequestMacros(): void
    {
        $factory = $this->app->make(UrlFactory::class);

        Request::macro('getUrlFromHeader', function (string $header, ?UrlContract $default = null) use ($factory): ?UrlContract {
            /** @var Request $this */
            $value = $this->header($header);

            if (is_string($value)) {
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

        Request::macro('getFullUrl', function () use ($factory): UrlContract {
            /** @var Request $this */
            return $factory->make($this->fullUrl());
        });
    }
}
