<?php

namespace App;

use App\Http\Controllers\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /*
     * laravel try to insert in tables the columns created_at and updated_at,
     * but i don't have this columns in my table
     *
     * @var bool
     */
    public $timestamps = false;

    /**
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

