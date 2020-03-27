<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductType extends Model
{
    public function setSlugAttribute($value) {
        // grab the title and slugify it
        $this->attributes['slug'] = Str::slug($this->name);
    }
    
    //Relationship
    public function products() 
    {
        return $this->hasMany('\App\Product');
    }

    public function sales() {
        return $this->hasManyThrough('\App\Sale', '\App\Product');
    }
}
