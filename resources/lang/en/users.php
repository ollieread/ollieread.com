<?php

return [

    'registration' => [
        'success' => 'Your account has been successfully created! To sign in, you\'ll need to verify your email using the link we just sent you',
    ],

    'verification' => [
        'success' => 'Your account has been successfully verified, you may now sign in.',
        'failure' => 'We were unable to verify your account.',
    ],

    'sign-in' => [
        'failure'      => 'The credentials provided are invalid',
        'inactive'     => 'Your account is inactive',
        'verification' => 'You haven\'t verified your email address',
    ],

    'details' => [
        'success' => 'Your account details have successfully been updated',
    ],

    'password' => [
        'success' => 'Your account password has successfully been updated',

        'forgot' => [
            'unknown'    => 'No user was found for the email address you provided',
            'inactive'   => 'That user account appears to be inactive',
            'unverified' => 'That user account hasn\'t been verified',
            'success'    => 'An email containing instructions has been sent to the email address provided',
        ],

        'reset' => [
            'token'   => 'This password reset has expired',
            'user'    => 'No user was found for the email address you provided',
            'success' => 'Your password was successfully reset, you may now login',
        ],
    ],

    'admin' => [
        'create' => [
            'success' => ':Entity successfully created',
        ],

        'edit' => [
            'success' => ':Entity successfully edited',
        ],

        'delete' => [
            'success' => ':Entity successfully deleted',
        ],

        'resend' => [
            'success' => 'Successfully resent the :Entity :mail',
        ],

        'toggle' => [
            'success' => 'Successfully :action the :entity',
        ],

        'user' => [
            'reset' => [
                'success' => 'A password reset has been sent to the user',
            ],
        ],
    ],
];
