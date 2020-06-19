<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
//use  Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use Sluggable;
//    use SoftDeletes;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = ['name','photo_id','quantity','price','slug','buyingPrice','saleSize','alert','expire_at'];
    protected $dates = ['deleted_at','expire_at'];

    public function inventory(){
        return $this->hasMany('App\Inventory');
    }

    public function sales(){
        return $this->hasMany('App\Sale');
    }

    public function loss(){
        return $this->hasMany('App\Loss');
    }
}
