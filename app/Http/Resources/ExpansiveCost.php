<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpansiveCost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [
            'id' => $this->id,
            'crater' => $this->crater,
            'itemCode' => $this->itemCode,
            'name' => $this->name,
            'paidValue' => $this->paidValue,
            'date' => $this->date,
            'state' => $this->state
        ];

        return $result;
    }
}
