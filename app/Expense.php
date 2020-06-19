<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['cash_detail','amount','user_id','cash_to'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
