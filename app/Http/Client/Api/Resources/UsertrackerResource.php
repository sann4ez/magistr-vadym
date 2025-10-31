<?php

namespace App\Http\Client\Api\Resources;

use App\Models\Item;
use App\Models\Usertracker;
use Illuminate\Http\Resources\Json\JsonResource;

class UsertrackerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        /** @var Usertracker $this */
        return [
            'id' => $this->id,
            'mood' => $this->getData('mood'),
            'anxiety' => $this->getData('anxiety'),
            'comment' => $this->getData('comment', ''),
            'created_at' => $this->getDatetime('created_at'),
            'fixed_at' => $this->getDatetime('fixed_at', 'Y-m-d'),
            'emotions' => $this->getEmotionsObj(),
        ];
    }
}
