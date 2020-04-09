<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Events;


use App\Events\Event;
use OneSite\Notify\Models\Notification;


/**
 * Class CreateNotifyRecord
 * @package OneSite\Notify\Events
 */
class CreateNotifyRecord extends Event
{

    /**
     * @var Notification
     */
    private $notification;

    /**
     * @var array
     */
    private $options = [];

    /**
     * CreateNotifyRecord constructor.
     * @param Notification $notification
     * @param array $options
     */
    public function __construct(Notification $notification, $options = [])
    {
        $this->notification = $notification;
        $this->options = $options;
    }

    /**
     * @return Notification
     */
    public function getNotification(): Notification
    {
        return $this->notification;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }


}
