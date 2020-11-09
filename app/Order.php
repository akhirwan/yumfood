<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $table = 'order';

    protected $fillable = ['user_id', 'dish_id', 'note', 'amount'];

    public function dish() {
        return $this->hasOne('App\Dish', 'id', 'dish_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
