<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OneSite\Notify\Models\Notification;
use OneSite\Notify\Models\NotificationDevice;
use OneSite\Notify\Models\NotificationRecord;
use OneSite\Notify\Services\Common\Notify;


/**
 * Class CreateNotifyRecord
 * @package OneSite\Notify\Listeners
 */
class CreateNotifyRecord
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $log;

    /**
     * CreateNotifyRecord constructor.
     */
    public function __construct()
    {
        $this->log = Log::channel('notification');;
    }

    /**
     * @param \OneSite\Notify\Events\CreateNotifyRecord $event
     */
    public function handle(\OneSite\Notify\Events\CreateNotifyRecord $event)
    {
        $notification = $event->getNotification();
        $option       = $event->getOptions();

        switch ($notification->receiver_type) {
            case Notify::RECEIVER_TYPE_ALL:
                $this->createRecordsByAll($notification, $option);

                break;
            case Notify::RECEIVER_TYPE_GROUP:
                $this->createRecordsByGroup($notification, $option);
    
                break;
            case Notify::RECEIVER_TYPE_USER:
                $this->createRecordsByUser($notification);

                break;
        }
    }

    /**
     * @param Notification $notification
     */
    private function createRecordsByGroup(Notification $notification, $option = [])
    {
        $this->log->info('Create notify admin records:', ['notification' => $notification]);
        $this->log->info('Log option:', ['option' => $option]);

        if (!isset($option['user_ids'])) {
            $this->log->error('SendNotifyByGroup: Không tìm thấy danh sách người dùng.');
            return;
        }
        $userIds = $option['user_ids'];
        $query   = "INSERT INTO notification_records (notification_id, device_id, user_id, `status`, is_read, created_at, updated_at)
        (
            SELECT
                :notification_id AS notification_id,
                nd.id as device_id,
                u.id AS user_id,
                'PENDING' AS `status`,
                0 AS is_read,
                NOW() AS created_at,
                NOW() AS updated_at
            FROM users AS u
            LEFT JOIN notification_devices AS nd ON u.id = nd.user_id
            WHERE u.is_active = 1
            AND u.id IN ({$userIds})
            ORDER BY created_at DESC
            LIMIT 1000
        )";
        DB::insert($query, ['notification_id' => $notification->id]);

        $notificationRecords = NotificationRecord::query()
            ->where('notification_id', $notification->id)
            ->where('status', Notify::STATUS_RECORD_PENDING)
            ->get();
        foreach ($notificationRecords as $notificationRecord) {
            sleep(2);
            event(new \OneSite\Notify\Events\SendNotifyRecord($notificationRecord));
        }
    }

    /**
     * @param Notification $notification
     */
    private function createRecordsByAll(Notification $notification, $option = [])
    {
        $this->log->info('Create notify admin records:', [
            'notification' => $notification
        ]);
        $this->log->info('Log option:', [
            'option' => $option
        ]);
        $limit = !empty($option['offset']) ? " limit {$option['offset']},5000" : '';

        $query = "INSERT INTO notification_records (notification_id, device_id, user_id, `status`, is_read, created_at, updated_at)
	                (
                        SELECT :notification_id as notification_id, (SELECT id FROM notification_devices nd WHERE user_id = u.id) as device_id, u.id as user_id, 'PENDING' as `status`, 0 as is_read, NOW() as created_at, NOW() as updated_at
                        FROM users AS u
                        WHERE u.is_active = 1
                        ORDER BY created_at DESC
                        {$limit}
	                )";

        DB::insert($query, [
            'notification_id' => $notification->id
        ]);

        $notificationRecords = NotificationRecord::query()
            ->where('notification_id', $notification->id)
            ->where('status', Notify::STATUS_RECORD_PENDING)
            ->get();
        foreach ($notificationRecords as $notificationRecord) {
            sleep(2);
            event(new \OneSite\Notify\Events\SendNotifyRecord($notificationRecord));
        }
    }

    /**
     * @param Notification $notification
     */
    private function createRecordsByUser(Notification $notification)
    {
        $this->log->info('Create notify member records:', [
            'notification' => $notification
        ]);

        $notificationDeviceId = 0;
        $notificationDevice = $this->getNotificationDevice($notification->receiver_id);
        if ($notificationDevice instanceof NotificationDevice) {
            $notificationDeviceId = $notificationDevice->id;
        }

        $notificationRecord = NotificationRecord::query()->create([
            'notification_id' => $notification->id,
            'device_id' => $notificationDeviceId,
            'user_id' => $notificationDevice->user_id,
            'status' => Notify::STATUS_RECORD_PENDING,
            'is_read' => 0
        ]);
        sleep(2);
        event(new \OneSite\Notify\Events\SendNotifyRecord($notificationRecord));
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    private function getNotificationDevice($userId)
    {
        return NotificationDevice::query()
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->first();
    }
}
