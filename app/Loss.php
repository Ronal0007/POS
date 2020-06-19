<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loss extends Model
{
    protected $fillable = ['product_id','quantity','loss'];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
