<?php
/**
 * Created by PhpStorm.
 * User: tungnt
 * Date: 10/22/19
 * Time: 22:32
 */

namespace OneSite\Notify\Http\Controllers\Admin;


use Illuminate\Http\Request;
use OneSite\Notify\Events\CreateNotify;
use OneSite\Notify\Events\CreateNotifyRecord;
use OneSite\Notify\Http\Controllers\Base;
use OneSite\Notify\Http\Requests\StoreNotifyRequest;
use OneSite\Notify\Http\Resources\NotificationResource;
use OneSite\Notify\Http\Resources\NotificationUserResource;
use OneSite\Notify\Models\Notification;
use OneSite\Notify\Services\Common\HashID;
use OneSite\Notify\Services\Common\Paginate;
use OneSite\Notify\Services\Common\Response;

/**
 * Class Notify
 * @package OneSite\Notify\Http\Controllers
 */
class Notify extends Base
{

    /**
     * @var NotificationResource $notificationResource
     */
    private $notificationResource;

    /**
     * Notify constructor.
     */
    public function __construct()
    {
        $this->notificationResource = \OneSite\Notify\Services\Common\Notify::getNotificationResource();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = !empty($request->per_page) ? $request->per_page : 25;

        $notifications = Notification::query()
            ->orderBy('created_at', 'desc');

        $notifications = $notifications->paginate($perPage);

        return Response::success([
            'notifications' => $this->notificationResource::collection($notifications),
            'meta_data' => Paginate::getMetaData($notifications)
        ]);
    }

    /**
     * @param StoreNotifyRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreNotifyRequest $request)
    {
        $user = $request->user();

        $attributes = $request->only([
            'title',
            'description',
            'receiver_type',
            'receiver_id',
            'action',
            'content',
            'send_data',
        ]);

        $attributes['creator_type'] = !empty($request->creator_type) ? $request->creator_type : \OneSite\Notify\Services\Common\Notify::CREATOR_TYPE_USER;
        $attributes['creator_id'] = $user->id;

        $notification = \OneSite\Notify\Models\Notification::query()->create($attributes);

        return Response::success([
            'notification' => new $this->notificationResource($notification)
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $notification = Notification::query()->where('id', $id)->first();

        if (!$notification instanceof Notification) {
            return Response::error(config('notification.error_code.notification_notfound', 1000), 'Notification not found.');
        }

        return Response::success([
            'notification' => new $this->notificationResource($notification)
        ]);
    }

    /**
     * @param StoreNotifyRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreNotifyRequest $request, $id)
    {
        $notification = Notification::query()->where('id', $id)->first();

        if (!$notification instanceof Notification) {
            return Response::error(config('notification.error_code.notification_notfound', 1000), 'Notification not found.');
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return Response::error(config('notification.error_code.notification_is_approved', 1000), 'Notification is approved.');
        }

        $attributes = $request->only([
            'title',
            'description',
            'receiver_type',
            'receiver_id',
            'action',
            'content',
        ]);

        $notification->update($attributes);

        return Response::success([
            'notification' => new $this->notificationResource($notification)
        ]);
    }


    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(Request $request, $id)
    {
        $notification = Notification::query()->where('id', $id)->first();

        if (!$notification instanceof Notification) {
            return Response::error(config('notification.error_code.notification_notfound', 1000), 'Notification not found.');
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return Response::error(config('notification.error_code.notification_is_approved', 1000), 'Notification is approved.');
        }

        $user = $request->user();

        $notification->moderator_id = $user->id;
        $notification->status = \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED;
        if (!$notification->save()) {
            return Response::error(config('notification.error_code.notification_is_not_approved', 1000), 'Notification is not approved.');
        }

        event(new CreateNotifyRecord($notification));

        return Response::success([
            'notification' => new $this->notificationResource($notification)
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $notification = Notification::query()->where('id', $id)->first();

        if (!$notification instanceof Notification) {
            return Response::error(config('notification.error_code.notification_notfound', 1000), 'Notification not found.');
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return Response::error(config('notification.error_code.notification_is_approved', 1000), 'Notification is approved.');
        }

        $notification->delete();

        return Response::success();
    }
}
