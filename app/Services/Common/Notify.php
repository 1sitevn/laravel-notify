<?php

namespace OneSite\Notify\Services\Common;

use Illuminate\Support\Facades\Route;
use OneSite\Notify\Services\Contract\Notification;
use OneSite\Notify\Services\Firebase;
use OneSite\Notify\Services\Test;

/**
 * Class Notify
 */
class Notify
{

    const PROVIDER_FIREBASE = 'firebase';
    const PROVIDER_TEST = 'test';

    const STATUS_PENDING = 'PENDING';
    const STATUS_APPROVED = 'APPROVED';
    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_SUCCESS = 'SUCCESS';

    const STATUS_RECORD_PENDING = 'PENDING';
    const STATUS_RECORD_PROCESSING = 'PROCESSING';
    const STATUS_RECORD_SUCCESS = 'SUCCESS';
    const STATUS_RECORD_FAIL = 'FAIL';

    const CREATOR_TYPE_USER = 'USER';
    const CREATOR_TYPE_ADMIN = 'ADMIN';

    const RECEIVER_TYPE_ALL = 'ALL';
    const RECEIVER_TYPE_USER = 'USER';
    const RECEIVER_TYPE_GROUP = 'GROUP';

    /**
     * @return array
     */
    public static function singletons()
    {
        $provider = config('notification.default');

        if ($provider == static::PROVIDER_FIREBASE) {
            return [
                Notification::class => Firebase::class
            ];
        }

        return [
            Notification::class => Test::class
        ];
    }

    /**
     *
     */
    public static function routes()
    {
        $defaultOptions = [
            'prefix' => 'notify',
            'middleware' => ['auth:api'],
            'admin_prefix' => 'admin/notify',
            'admin_middleware' => ['auth:api']
        ];

        $options = array_merge($defaultOptions, config('notification.route'));

        Route::group(['prefix' => $options['prefix']], function () use ($options) {
            Route::get('health', 'Test@index');

            Route::group(['middleware' => $options['middleware']], function () {
                Route::post('device', 'Device@store');
            });
        });

        Route::group(['prefix' => $options['admin_prefix']], function () use ($options) {
            Route::group(['middleware' => $options['admin_middleware']], function () {
                Route::get('{id}', 'Admin\Notify@show');
                Route::post('', 'Admin\Notify@store');
                Route::post('{id}', 'Admin\Notify@update');
                Route::post('{id}/approve', 'Admin\Notify@approve');
                Route::delete('{id}', 'Admin\Notify@destroy');
            });
        });
    }
}
