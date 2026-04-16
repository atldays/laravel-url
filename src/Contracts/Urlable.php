<?php

declare(strict_types=1);

namespace Atldays\Url\Contracts;

interface Urlable
{
    public function getUrl(): Url;
}
