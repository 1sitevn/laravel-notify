<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use OneSite\Notify\Models\NotificationDevice;
use OneSite\Notify\Models\NotificationRecord;
use OneSite\Notify\Services\Common\Notify;


/**
 * Class Test
 * @package OneSite\Notify\Listeners
 */
class SendNotify
{

    /**
     * @param \OneSite\Notify\Events\SendNotify $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(\OneSite\Notify\Events\SendNotify $event)
    {
        $userId = $event->getUserId();
        $data = $event->getData();

        $notification = \OneSite\Notify\Models\Notification::query()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'receiver_type' => Notify::RECEIVER_TYPE_USER,
            'receiver_id' => $userId,
            'action' => $data['action'],
            'content' => $data['content'],
            'status' => Notify::STATUS_APPROVED,
            'creator_type' => Notify::CREATOR_TYPE_USER,
            'creator_id' => $userId,
        ]);

        if (!$notification instanceof \OneSite\Notify\Models\Notification) {
            return;
        }

        $notificationDevice = NotificationDevice::query()->where('user_id', $userId)->first();
        if (!$notificationDevice instanceof NotificationDevice) {
            return;
        }

        $notificationRecord = NotificationRecord::query()->create([
            'notification_id' => $notification->id,
            'device_id' => $notificationDevice->id,
            'user_id' => $notification->id,
            'status' => Notify::STATUS_RECORD_PENDING,
            'is_read' => 0
        ]);

        if (!$notificationRecord instanceof NotificationRecord) {
            return;
        }

        event(new \OneSite\Notify\Events\SendNotifyRecord($notificationRecord));
    }
}
