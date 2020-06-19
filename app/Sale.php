<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['product_id','quantity','amount','t_number','discount','buyingPrice'];

    public function product(){
       return $this->belongsTo('App\Product');
    }
}
