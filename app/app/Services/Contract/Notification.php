<?php
/**
 * Created by TungNT.
 * User: tungnt
 * Date: 4/19/19
 * Time: 15:14
 */

namespace OneSite\Notify\Services\Contract;


/**
 * Interface Notification
 * @package OneSite\Notify\Services\Contract
 */
interface Notification
{

    /**
     * @param $to
     * @param $data
     * @param array $options
     * @return mixed
     */
    public function send($to, $data, $options = []);
}
