<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:32
 */

namespace OneSite\Notify\Http\Controllers;


use Illuminate\Http\Request;
use OneSite\Notify\Events\CreateNotify;
use OneSite\Notify\Events\CreateNotifyRecord;
use OneSite\Notify\Http\Requests\StoreNotifyRequest;
use OneSite\Notify\Http\Resources\NotificationResource;
use OneSite\Notify\Http\Resources\NotificationUserResource;
use OneSite\Notify\Models\Notification;
use OneSite\Notify\Models\NotificationRecord;
use OneSite\Notify\Services\Common\Paginate;

/**
 * Class Notify
 * @package OneSite\Notify\Http\Controllers
 */
class Notify extends Base
{
    /**
     * @var NotificationUserResource $notificationUserResource
     */
    private $notificationUserResource;

    /**
     * Notify constructor.
     */
    public function __construct()
    {
        $this->notificationUserResource = config('notification.class.notification_user_resource', NotificationUserResource::class);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $perPage = !empty($request->per_page) ? $request->per_page : 25;

        $notifications = NotificationRecord::query()
            ->where('user_id', $user->id)
            ->where('status', \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS)
            ->orderBy('created_at', 'desc');

        $notifications = $notifications->paginate($perPage);

        return response()->json([
            'notifications' => $this->notificationUserResource::collection($notifications),
            'meta_data' => Paginate::getMetaData($notifications)
        ]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        $notification = NotificationRecord::query()
            ->where('user_id', $user->id)
            ->where('status', \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS)
            ->first();

        if (!$notification instanceof NotificationRecord) {
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        return response()->json([
            'notification' => new $this->notificationUserResource($notification)
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function read(Request $request, $id)
    {
        $user = $request->user();

        $notification = NotificationRecord::query()
            ->where('user_id', $user->id)
            ->where('status', \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS)
            ->first();

        if (!$notification instanceof NotificationRecord) {
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        $notification->is_read = 1;
        $notification->save();

        return response()->json([
            'notification' => new $this->notificationUserResource($notification)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readAll(Request $request)
    {
        $user = $request->user();

        $notification = NotificationRecord::query()
            ->where('user_id', $user->id)
            ->update([
                'is_read' => 1
            ]);

        return response()->json([]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $notification = NotificationRecord::query()
            ->where('user_id', $user->id)
            ->where('status', \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS)
            ->first();

        if (!$notification instanceof NotificationRecord) {
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        $notification->delete();

        return response()->json([]);
    }
}
