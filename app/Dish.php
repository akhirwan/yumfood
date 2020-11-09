<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    //
    protected $fillable = ['title', 'price', 'vendor_id'];

    public function tags() {
        return $this->morphToMany('App\Tag', 'taggable');
    }

    public function vendor() {
        return $this->belongsTo('App\Vendor', 'id');
    }
}
