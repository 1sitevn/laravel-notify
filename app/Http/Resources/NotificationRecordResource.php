<?php

namespace OneSite\Notify\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NotificationRecordResource
 * @package OneSite\Notify\Http\Resources
 */
class NotificationRecordResource extends JsonResource
{

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'notification_id' => !empty($this->notification_id) ? $this->notification_id : 0,
            'device_id' => !empty($this->device_id) ? $this->device_id : 0,
            'status' => !empty($this->status) ? $this->status : '',
            'is_read' => !empty($this->is_read) ? $this->is_read : 0,
            'created_at' => !empty($this->created_at) ? $this->created_at : '',
            'updated_at' => !empty($this->updated_at) ? $this->updated_at : ''
        ];
    }
}
