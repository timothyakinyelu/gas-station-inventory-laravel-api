<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Users extends JsonResource
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
            'email' => $this->email,
            'permission' => (($this->permission == config('constants.ADMIN')) ? 'admin' : (($this->permission == config('constants.SUPER-ADMIN')) ? 'super_admin' : 'data_entry')),
            'station' => $this->station->name,
        ];
    }
}
