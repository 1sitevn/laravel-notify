<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:54
 */

namespace OneSite\Notify\Events;


use App\Events\Event;


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
     * @var array
     */
    private $data = [];

    /**
     * SendNotify constructor.
     * @param $userId
     * @param array $data
     */
    public function __construct($userId, array $data = [])
    {
        $this->userId = $userId;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


}
