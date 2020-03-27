<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Station extends Model
{

    public function setSlugAttribute($value) {
        // grab the title and slugify it
        $this->attributes['slug'] = Str::slug($this->name);
    }

    //Relationships
    public function company() 
    {
        return $this->belongsTo('\App\Company');
    }

    public function users() 
    {
        return $this->hasMany('\App\User');
    }

    public function employees() 
    {
        return $this->hasMany('\App\Employee');
    }

    public function stocks() 
    {
        return $this->hasMany('\App\Stock');
    }

    public function supplies() 
    {
        return $this->hasMany('\App\Supply');
    }

    public function expenses() 
    {
        return $this->hasMany('\App\Expense');
    }

    public function sales() 
    {
        return $this->hasMany('\App\Sale');
    }
}
