<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EmployeeResource extends JsonResource
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
            'station' => $this->when($this->station, function () {
                return $this->station->name;
            }),
            'name' => $this->firstName . ' ' . $this->lastName,
            'phone' => $this->phone,
            'role' => $this->role,
            'date_hired' => (Carbon::parse($this->hired)->toDateString()),
        ];
    }
}
