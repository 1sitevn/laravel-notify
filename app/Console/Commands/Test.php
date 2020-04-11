<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 23:50
 */

namespace OneSite\Notify\Console\Commands;


use Illuminate\Console\Command;
use OneSite\Notify\Events\SendNotify;
use OneSite\Notify\Services\Notification;


/**
 * Class Test
 * @package OneSite\Notify\Console\Commands
 */
class Test extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'notify:test {--userId=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Notify test module command";

    /**
     *
     */
    public function handle()
    {
        $this->line('Notify test module command...');

        $userId = $this->option('userId');
        if (empty($userId)) {
            return;
        }

        $title = 'Test notification';
        $description = 'Test notification';
        $action = 'link';
        $content = [
            'id' => 'zRrj7Yme'
        ];
        $extraData = [
            'test_id' => 123312
        ];
        $nofificationInfo = new Notification($title, $description, $action, $content, $extraData);

        event(new SendNotify($userId, $nofificationInfo));
    }
}
