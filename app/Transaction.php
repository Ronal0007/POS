<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $primaryKey = 'number';
    public $incrementing = false;
    protected $fillable = ["number","user_id","status"];

    public function tempProducts(){
        return $this->hasMany('App\Tempsale','t_number','number');
    }
}
