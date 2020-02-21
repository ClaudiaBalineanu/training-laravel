<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function value()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class)
            ->addSelect([
                \DB::raw('SUM(price) AS total')
            ])
            ->groupBy('order_id');
    }
}
