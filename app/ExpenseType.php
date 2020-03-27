<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    //Relationship
    public function expenses() 
    {
        return $this->hasMany('\App\Expense');
    }
}
