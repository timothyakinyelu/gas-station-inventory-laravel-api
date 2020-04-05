<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
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
            'product_code' => $this->when($this->product_code, function () {
                return $this->product_code->code;
            }),
            $this->mergeWhen($this->pump_code, [
                'pump_code' => $this->when($this->pump_code, function () {
                    return $this->pump_code;
                }),
                'start_metre' => $this->when($this->pump_code, function () {
                    return $this->start_metre;
                }),
                'end_metre' => $this->when($this->pump_code, function () {
                    return $this->end_metre;
                }),
            ]),
            'quantity_sold' => $this->quantity_sold,
            'price' => $this->unit_price,
            'total_sale' => $this->amount
        ];
    }
}
