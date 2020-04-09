<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 12/20/19
 * Time: 22:27
 */

namespace OneSite\Notify\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class NotificationDevice
 * @package OneSite\Notify\Models
 */
class NotificationDevice extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'token',
        'platform',
    ];

}
