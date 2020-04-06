<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:53
 */

namespace OneSite\Notify\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use OneSite\Notify\Events\CreateNotify;
use OneSite\Notify\Events\CreateNotifyRecord;
use OneSite\Notify\Events\SendNotify;
use OneSite\Notify\Events\SendNotifyRecord;


/**
 * Class EventServiceProvider
 * @package OneSite\Notify\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $listen = [
        CreateNotify::class => [
            \OneSite\Notify\Listeners\CreateNotify::class
        ],
        CreateNotifyRecord::class => [
            \OneSite\Notify\Listeners\CreateNotifyRecord::class
        ],
        SendNotify::class => [
            \OneSite\Notify\Listeners\SendNotify::class
        ],
        SendNotifyRecord::class => [
            \OneSite\Notify\Listeners\SendNotifyRecord::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
