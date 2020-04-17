<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StockResource extends JsonResource
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
            'tank_code' => $this->tank_code === null ? 'NA' : $this->tank_code,
            'start_stock' => $this->open_stock,
            'end_stock' => $this->close_stock,
            'sold' => $this->inventory_sold,
            'received' => $this->inventory_received,
            'stock_date' => (Carbon::parse($this->date_of_inventory)->toDateString()),
        ];
    }
}
