<?php

namespace App;

use App\Http\Controllers\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /*
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class);
    }
}

