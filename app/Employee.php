<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    //Relationships
    public function station() 
    {
        return $this->belongsTo('\App\Station')
        ->withDefault([
            'slug' => 0
        ]);
    }

    public function users() 
    {
        return $this->hasMany('\App\User');
    }
}
