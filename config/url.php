<?php

declare(strict_types=1);

use Atldays\Url\Sanitizers\ControlCharsSanitizer;
use Atldays\Url\Sanitizers\HeaderValueSanitizer;
use Atldays\Url\Sanitizers\Utf8Sanitizer;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Sanitizer Profile
    |--------------------------------------------------------------------------
    |
    | This profile will be used whenever a URL is created through the factory
    | or facade without explicitly passing a profile name. Profiles allow you
    | to group multiple sanitizers into named pipelines for different inputs.
    |
    */
    'default_profile' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Sanitizer Profiles
    |--------------------------------------------------------------------------
    |
    | Each profile is a pipeline of sanitizer classes that will be applied in
    | order before the URL object is created. This makes it easy to keep the
    | core URL value object clean while tailoring cleanup rules per use case.
    |
    | Available profiles in this package:
    |
    | - default: General-purpose URL input normalization.
    | - header:  URL cleanup intended for header values like Origin/Referer.
    |
    */
    'profiles' => [
        /*
        |--------------------------------------------------------------------------
        | Default Profile
        |--------------------------------------------------------------------------
        |
        | The default profile is intended for regular application input. It
        | strips control characters before parsing and then removes invalid
        | UTF-8 sequences so the URL parser works with a clean string.
        |
        */
        'default' => [
            ControlCharsSanitizer::class,
            Utf8Sanitizer::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | Header Profile
        |--------------------------------------------------------------------------
        |
        | The header profile is designed for values coming from HTTP headers.
        | It first trims header wrappers and whitespace noise, then removes
        | control characters, and finally normalizes invalid UTF-8 before
        | URL parsing begins.
        |
        */
        'header' => [
            HeaderValueSanitizer::class,
            ControlCharsSanitizer::class,
            Utf8Sanitizer::class,
        ],
    ],
];
