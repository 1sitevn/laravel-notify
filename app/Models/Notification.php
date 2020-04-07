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
 * Class Notification
 * @package OneSite\Notify\Models
 */
class Notification extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'receiver_type',
        'receiver_id',
        'status',
        'action',
        'content',
        'send_data',
        'creator_type',
        'creator_id',
        'moderator_id',
    ];

}
