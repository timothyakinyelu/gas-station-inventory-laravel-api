<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Auth;

class UserResource extends JsonResource
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
            'name' => $this->employee->firstName . ' ' . $this->employee->lastName,
            'email' => $this->email,
            'role' => $this->employee->role,
            'permission' => (($this->permission == config('constants.ADMIN')) ? 'admin' : (($this->permission == config('constants.SUPER-ADMIN')) ? 'super_admin' : 'data_entry')),
            'station' => $this->station->slug,
            'stationID' => $this->station_id,
            'company' => $this->company->slug,
            'companyID' => $this->company->id,
            $this->mergeWhen(Auth::user() && Auth::user()->permission >= config('constants.ADMIN'), [
                'id' => $this->id,
                'stationName' => $this->station->name,
                'permissionID' => $this->permission
            ]), 
        ];
    }
}
