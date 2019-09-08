<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'hashids' => [
        'categories' => env('HASHIDS_CATEGORIES'),
        'posts'      => env('HASHIDS_POSTS'),
        'projects'   => env('HASHIDS_PROJECTS'),
        'series'     => env('HASHIDS_SERIES'),
        'tags'       => env('HASHIDS_TAGS'),
    ],

    'github' => [
        'client_id'     => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/social/github/response',
    ],

    'google' => [
        'client_id'     => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/social/google/response',
    ],

    'twitter' => [
        'client_id'     => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect'      => env('APP_URL') . '/social/twitter/response',
    ],

    'discord' => [
        'client_id'     => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'token'         => env('DISCORD_TOKEN'),
        'redirect'      => env('APP_URL') . '/social/discord/response',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'mailchimp' => [
        'key'  => env('MAILCHIMP_KEY'),
        'list' => '3344c7d684',
    ],
];
