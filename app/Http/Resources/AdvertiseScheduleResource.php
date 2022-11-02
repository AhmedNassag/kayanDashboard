<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdvertiseScheduleResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'start' => $this->start,
            'end' => $this->end,
        ];
    }
}
