<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    protected $fillable = ['name', 'product_type_id', 'product_code_id', 'price'];
    public $timestamps = false;
    
    //Relationship
    public function product_type() 
    {
        return $this->belongsTo('\App\ProductType');
    }

    public function sales() 
    {
        return $this->hasMany('\App\Sale');
    }

    public function product_code()
    {
        return $this->belongsTo('\App\ProductCode');
    }
}
