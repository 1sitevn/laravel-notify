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

    'aliases' => [
        'user_model' => \App\Models\User::class,
        'notification_info' => \OneSite\Notify\Services\Notification::class,
        'notification_resource' => \OneSite\Notify\Http\Resources\NotificationResource::class,
        'notification_record_resource' => \OneSite\Notify\Http\Resources\NotificationRecordResource::class,
        'notification_device_resource' => \OneSite\Notify\Http\Resources\NotificationDeviceResource::class,
        'notification_user_resource' => \OneSite\Notify\Http\Resources\NotificationUserResource::class,
    ],

    'hash_id' => [
        'salt' => env('NOTIFY_HASHID_SALT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9'),
        'alphabet' => env('NOTIFY_HASHID_ALPHABET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ012345678'),
    ],

    'test' => [
        'device' => 'ekvYWW8fhxg:APA91bHdTnUzTOEQpU0Uh6PvzT9-REMosOq2NvjUaHkK9WoVKf-OO9cOSCUgW1oqWcCrnlwULhBAAtl1JqXk3jUAYdYkfYq4rkHM6wyV66S0IBJ14HtANQ3aVZBLzh4Z-8givFwXmxrG'
    ],

    'error_code' => [
        'notification_notfound' => 1000,
        'notification_is_approved' => 1001,
        'notification_is_not_approved' => 1002,
    ],

    'types' => [
        'account_register' => [
            'id' => 'account_register',
            'name' => 'Đăng ký tài khoản thành công'
        ],
        'account_verified_ok' => [
            'id' => 'account_verified_ok',
            'name' => 'Xác thực tài khoản thành công'
        ],
        'account_not_verified' => [
            'id' => 'account_not_verified',
            'name' => 'Tài khoản chưa xác thực'
        ],
        'account_verified_email_ok' => [
            'id' => 'account_verified_email_ok',
            'name' => 'Xác thực email thành công'
        ],
    ]
];
