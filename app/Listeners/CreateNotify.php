<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use OneSite\Notify\Http\Resources\NotificationResource;


/**
 * Class CreateNotify
 * @package OneSite\Notify\Listeners
 */
class CreateNotify
{


    /**
     * @param \OneSite\Notify\Events\CreateNotify $event
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function handle(\OneSite\Notify\Events\CreateNotify $event)
    {
        $data = \OneSite\Notify\Models\Notification::query()->create($event->getData());

        return new NotificationResource($data);
    }
}
