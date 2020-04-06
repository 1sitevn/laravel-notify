<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 11/22/19
 * Time: 10:59
 */

return [
    'default' => env('NOTIFY_DEFAULT', 'firebase'),

    'fcm' => [
        'api_url' => env('NOTIFICATION_FCM_API_URL', 'https://fcm.googleapis.com'),
        'api_key' => env('NOTIFICATION_FCM_API_KEY', '')
    ],

    'route' => [
        'prefix' => 'notify',
        'middleware' => ['auth:api'],

        'admin_prefix' => 'admin/notify',
        'admin_middleware' => ['auth:api']
    ],

    'test' => [
        'device' => 'xyz'
    ],

    'hash_id' => [
        'salt' => env('HASHID_SALT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'),
        'alphabet' => env('HASHID_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678'),
    ],
];
