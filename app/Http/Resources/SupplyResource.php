<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class SupplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => $this->when($this->product, function () {
                return $this->product->name;
            }),
            'supply_price' => $this->supply_price,
            'received' => $this->inventory_received,
            'supply_date' => (Carbon::parse($this->date_of_supply)->toDateString()),
        ];
    }
}
