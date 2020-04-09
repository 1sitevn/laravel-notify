<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:36
 */

namespace OneSite\Notify\Services;


use Illuminate\Support\Facades\Log;
use OneSite\Notify\Services\Contract\Notification;

/**
 * Class Test
 * @package OneSite\Notify\Services
 */
class Test implements Notification
{
    /**
     * @param $to
     * @param $data
     * @param array $options
     * @return mixed|void
     */
    public function send($to, $data, $options = [])
    {
        $log = Log::channel('notification');

        $log->info('Test notification...!');
    }

}
