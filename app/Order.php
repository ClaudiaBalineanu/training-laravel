<?php

namespace App;

use App\Http\Controllers\OrderProduct;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
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
    public function products()
    {
        return $this->belongsToMany(Product::class)->using(OrderProduct::class);
    }
}
