<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
    public function orders()
    {
        return $this->belongsToMany(Order::class)->using(OrderProduct::class);
    }

    /**
     * @return string
     */
    public function getImagePath()
    {
        //!!!!!!!!!!! ATENTION !!!!!!!!! from \\ to / for spa
        return public_path('images') . '/' . $this->image;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return url('images/' . $this->image);
    }

}

