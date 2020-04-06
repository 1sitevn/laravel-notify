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
use OneSite\Notify\Models\Notification;

/**
 * Class Notify
 * @package OneSite\Notify\Http\Controllers
 */
class Notify extends Base
{

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
        ]);

        $attributes['creator_type'] = !empty($request->creator_type) ? $request->creator_type : \OneSite\Notify\Services\Common\Notify::CREATOR_TYPE_USER;
        $attributes['creator_id'] = $user->id;

        $notification = event(new CreateNotify($attributes));

        return response()->json([
            'notification' => $notification
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
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        return response()->json([
            'notification' => new NotificationResource($notification)
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
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return response()->json([
                'error' => 'Notification is approved.'
            ]);
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

        return response()->json([
            'notification' => new NotificationResource($notification)
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
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return response()->json([
                'error' => 'Notification is approved.'
            ]);
        }

        $user = $request->user();

        $notification->moderator_id = $user->id;
        $notification->status = \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED;
        if (!$notification->save()) {
            return response()->json([
                'error' => 'Notification is not approved.'
            ]);
        }

        event(new CreateNotifyRecord($notification));

        return response()->json([
            'notification' => new NotificationResource($notification)
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
            return response()->json([
                'error' => 'Notification not found.'
            ]);
        }

        if (in_array($notification->status, [
            \OneSite\Notify\Services\Common\Notify::STATUS_APPROVED,
            \OneSite\Notify\Services\Common\Notify::STATUS_PROCESSING,
            \OneSite\Notify\Services\Common\Notify::STATUS_SUCCESS
        ])) {
            return response()->json([
                'error' => 'Notification is approved.'
            ]);
        }

        $notification->delete();

        return response()->json([]);
    }
}
