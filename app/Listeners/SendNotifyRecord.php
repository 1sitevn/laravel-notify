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
use OneSite\Notify\Services\Common\Notify;
use OneSite\Notify\Services\Contract\Notification;


/**
 * Class SendNotifyRecord
 * @package OneSite\Notify\Listeners
 */
class SendNotifyRecord implements ShouldQueue
{

    /**
     * @param \OneSite\Notify\Events\SendNotifyRecord $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(\OneSite\Notify\Events\SendNotifyRecord $event)
    {
        $notificationRecord = $event->getNotificationRecord();

        $notification = $notificationRecord->notification;
        if (!$notification instanceof \OneSite\Notify\Models\Notification) {
            return;
        }

        $notificationDevice = $notificationRecord->device;
        if (!$notificationDevice instanceof NotificationDevice) {
            return;
        }

        $notificationRecord->update([
            'status' => Notify::STATUS_RECORD_PROCESSING
        ]);

        $notificationService = app()->make(Notification::class);
        $sendInfo = $notificationService->send($notificationDevice->token, [
            'notification' => [
                'id' => $notificationRecord->id,
                'title' => $notification->title,
                'description' => $notification->description,
                'action' => $notification->action,
                'content' => $notification->content
            ]
        ]);

        if (!empty($sendInfo['error'])) {
            $notificationRecord->update([
                'status' => Notify::STATUS_RECORD_FAIL
            ]);

            return;
        }

        $notificationRecord->update([
            'status' => Notify::STATUS_RECORD_SUCCESS
        ]);
    }
}
