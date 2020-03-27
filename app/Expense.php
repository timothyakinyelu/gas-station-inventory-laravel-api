<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    protected $guarded = ['id'];
    //Relationships
    public function station() 
    {
        return $this->belongsTo('\App\Station');
    }

    public function expense_type() 
    {
        return $this->belongsTo('\App\ExpenseType');
    }
}
