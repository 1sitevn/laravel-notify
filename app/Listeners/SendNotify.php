<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Listeners;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use OneSite\Notify\Models\NotificationDevice;
use OneSite\Notify\Models\NotificationRecord;
use OneSite\Notify\Services\Common\Notify;
use OneSite\Notify\Services\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;


/**
 * Class Test
 * @package OneSite\Notify\Listeners
 */
class SendNotify implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public $afterCommit = true;

    private $log;

    /**
     * CreateNotifyRecord constructor.
     */
    public function __construct()
    {
        $this->log = Log::channel('notification');
        $this->delay(10);
    }

    /**
     * @param \OneSite\Notify\Events\SendNotify $event
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(\OneSite\Notify\Events\SendNotify $event)
    {
        $userId = $event->getUserId();
        /**
         * @var Notification $data
         */
        $data = $event->getData();

        $notification = \OneSite\Notify\Models\Notification::query()->create([
            'title' => $data->getTitle(),
            'description' => $data->getDescription(),
            'receiver_type' => Notify::RECEIVER_TYPE_USER,
            'receiver_id' => $userId,
            'action' => $data->getAction(),
            'content' => json_encode($data->getContent()),
            'send_data' => json_encode($data->getSendData()),
            'status' => Notify::STATUS_APPROVED,
            'creator_type' => Notify::CREATOR_TYPE_USER,
            'creator_id' => $userId,
        ]);

        if (!$notification instanceof \OneSite\Notify\Models\Notification) {
            return;
        }

        $notificationDeviceId = 0;
        $notificationDevice = NotificationDevice::query()
            ->where('user_id', $userId)
            ->first();
        if ($notificationDevice instanceof NotificationDevice) {
            $notificationDeviceId = $notificationDevice->id;
        }

        $dataRecord = [
            'notification_id' => $notification->id,
            'device_id' => $notificationDeviceId,
            'user_id' => $userId,
            'status' => Notify::STATUS_RECORD_PENDING,
            'is_read' => 0
        ];

        $notificationRecord = new NotificationRecord();
        $notificationRecord->fill($dataRecord);

        if ($notificationRecord->save() && $notificationDevice instanceof NotificationDevice) {
            $this->log->info('Line 97 Create notify records:', [
                'notificationRecord' => $notificationRecord
            ]);
            event(new \OneSite\Notify\Events\SendNotifyRecord($notificationRecord, $notificationRecord->id));
        }
    }
}
