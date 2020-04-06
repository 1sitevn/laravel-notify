<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Events;


use App\Events\Event;
use OneSite\Notify\Models\NotificationRecord;


/**
 * Class SendNotifyRecord
 * @package OneSite\Notify\Events
 */
class SendNotifyRecord extends Event
{

    /**
     * @var NotificationRecord
     */
    private $notificationRecord;

    /**
     * SendNotifyRecord constructor.
     * @param NotificationRecord $notificationRecord
     */
    public function __construct(NotificationRecord $notificationRecord)
    {
        $this->notificationRecord = $notificationRecord;
    }

    /**
     * @return NotificationRecord
     */
    public function getNotificationRecord(): NotificationRecord
    {
        return $this->notificationRecord;
    }

}
