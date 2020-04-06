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
    protected $signature = 'notify:test';
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

        $userId = 3;

        $data = [
            'title' => 'Test notification',
            'description' => 'Test notification',
            'action' => 'LINK',
            'content' => 'https://24h.com.vn',
        ];

        event(new SendNotify($userId, $data));
    }
}
