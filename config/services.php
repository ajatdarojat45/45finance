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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

   'facebook' => [
      'client_id' => '198844574172813',
      'client_secret' => '9558fddb58874f70380d20a13b33e344',
      'redirect' => 'http://localhost/uangKita/public/auth/facebook/callback',
   ],

   'twitter' => [
      'client_id' => 'rjG6sbRM6SwnULwt2gO42gb45',
      'client_secret' => 'bgSG0avol8Kam9IncP1gvBoCwxFS6JOTYthAtQd8tMd4NtuYP8',
      'redirect' => 'http://localhost/uangKita/public/auth/twitter/callback',
   ],

   'google' => [
      'client_id' => '769996409959-2iieearvc8jirgqq7d75n82cau9deaoc.apps.googleusercontent.com',
      'client_secret' => 'UfIIABwhw1qo64ubuv-08mKN',
      'redirect' => 'http://localhost/uangKita/public/auth/google/callback',
   ],

   'github' => [
      'client_id' => '351d68b2b3c65214ae78',
      'client_secret' => '31f223a18ef953b436cf53c82df32383605ed09b',
      'redirect' => 'http://localhost/uangKita/public/auth/github/callback',
   ],

];
