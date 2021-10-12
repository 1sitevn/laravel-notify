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
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use OneSite\Notify\Models\NotificationDevice;
use OneSite\Notify\Models\NotificationRecord;
use OneSite\Notify\Services\Common\HashID;
use OneSite\Notify\Services\Common\Notify;
use OneSite\Notify\Services\Contract\Notification;


/**
 * Class SendNotifyRecord
 * @package OneSite\Notify\Listeners
 */
class SendNotifyRecord implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    public $afterCommit = true;

    protected $notifyRecord;

    private $log;

    /**
     * Create a new job instance.
     *
     */
    public function __construct()
    {
        $this->log = Log::channel('notification');
        $this->delay(10);
    }


    public function handle(\OneSite\Notify\Events\SendNotifyRecord $event)
    {
        $this->notifyRecord = $event;

        $notificationRecord = $this->notifyRecord->getNotificationRecord();

        if (!$notificationRecord instanceof NotificationRecord) {
            $notificationRecord->update([
                'status' => Notify::STATUS_RECORD_NOTIFICATION_NOT_FOUND
            ]);

            return;
        }

        $notification = $notificationRecord->notification;
        if (!$notification instanceof \OneSite\Notify\Models\Notification) {
            $notificationRecord->update([
                'status' => Notify::STATUS_RECORD_NOTIFICATION_NOT_FOUND
            ]);

            return;
        }

        $notificationDevice = $notificationRecord->device;
        if (!$notificationDevice instanceof NotificationDevice) {
            $notificationRecord->update([
                'status' => Notify::STATUS_RECORD_DEVICE_NOT_EXISTS
            ]);

            return;
        }

        $notificationRecord->update([
            'status' => Notify::STATUS_RECORD_PROCESSING
        ]);

        $notificationService = app()->make(Notification::class);

        $sendData = (array)json_decode($notification->send_data);
        $sendData = array_merge([
            'id' => HashID::idEncode($notificationRecord->id),
            'body' => $notification->description,
        ], $sendData);

        $this->log->info('Line 91 send notify: ', [
            'sendData' => $sendData
        ]);

        $sendInfo = $notificationService->send($notificationDevice->token, [
            'notification' => $sendData
        ]);

        if (!empty($sendInfo['error'])) {
            $notificationRecord->update([
                'status' => Notify::STATUS_RECORD_FAIL,
                'meta_data' => json_encode($sendInfo)
            ]);

            return;
        }

        $notificationRecord->update([
            'status' => Notify::STATUS_RECORD_SUCCESS,
            'meta_data' => json_encode($sendInfo)
        ]);
    }
}
