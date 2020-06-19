<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tempsale extends Model
{
    protected $fillable = ['product_id','amount','t_number','discount','quantity'];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
