<?php

namespace OneSite\Notify\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class NotificationResource
 * @package OneSite\Notify\Http\Resources
 */
class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => !empty($this->title) ? $this->title : '',
            'description' => !empty($this->description) ? $this->description : '',
            'receiver_type' => !empty($this->receiver_type) ? $this->receiver_type : '',
            'receiver_id' => !empty($this->receiver_id) ? $this->receiver_id : 0,
            'status' => !empty($this->status) ? $this->status : '',
            'action' => !empty($this->action) ? $this->action : '',
            'content' => !empty($this->content) ? $this->content : '',
            'creator_type' => !empty($this->creator_type) ? $this->creator_type : '',
            'creator_id' => !empty($this->creator_id) ? $this->creator_id : 0,
            'moderator_id' => !empty($this->moderator_id) ? $this->moderator_id : 0,
            'created_at' => !empty($this->created_at) ? $this->created_at : '',
            'updated_at' => !empty($this->updated_at) ? $this->updated_at : ''
        ];
    }
}
