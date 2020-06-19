<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = ['quantity','product_id','cost','newPrice'];

    public function product(){
        return $this->belongsTo('App\Product');
    }
}
