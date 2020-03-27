<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCode extends Model
{
    //Relationship
    public function products() 
    {
        return $this->hasMany('\App\Product');
    }

    public function sales() 
    {
        return $this->hasMany('\App\Sale');
    }

    public function stocks() 
    {
        return $this->hasMany('\App\Stock');
    }

    public function supplies() 
    {
        return $this->hasMany('\App\Supply');
    }
}
