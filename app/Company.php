<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Company extends Model
{
    public function setSlugAttribute($value) {
        // grab the title and slugify it
        $this->attributes['slug'] = Str::slug($this->name);
    }

    //Relationships
    public function users() 
    {
        return $this->hasMany('\App\User');
    }

    public function stations() 
    {
        return $this->hasMany('\App\Station');
    }

    public function employees() 
    {
        return $this->hasMany('\App\Employee');
    }

    public function products() 
    {
        return $this->hasMany('\App\Product');
    }
}
