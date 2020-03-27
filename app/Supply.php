<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supply extends Model
{
    protected $guarded = ['id'];
    
    //Relationships
    public function product() 
    {
        return $this->belongsTo('\App\Product');
    }

    public function product_code() 
    {
        return $this->belongsTo('\App\ProductCodes');
    }

    public function station() 
    {
        return $this->belongsTo('\App\Station');
    }
}
