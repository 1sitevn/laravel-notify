<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Events;


use App\Events\Event;
use OneSite\Notify\Services\Notification;


/**
 * Class SendNotify
 * @package OneSite\Notify\Events
 */
class SendNotify extends Event
{
    /**
     * @var
     */
    private $userId;

    /**
     * @var Notification|null
     */
    private $data = null;


    /**
     * SendNotify constructor.
     * @param $userId
     * @param $data
     * @throws \Exception
     */
    public function __construct($userId, $data)
    {
        $this->userId = $userId;

        if ($data instanceof Notification || is_subclass_of($data, Notification::class)) {
            $this->data = $data;
        } else {
            throw new \Exception("Notification info is not valid!");
        }
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }


    /**
     * @return Notification|null
     */
    public function getData()
    {
        return $this->data;
    }


}
