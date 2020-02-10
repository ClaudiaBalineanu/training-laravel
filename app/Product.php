<?php


namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // laravel try to insert in tables the columns created_at and updated_at , but i don't have this columns in my table
    public $timestamps = false;

    // fillable is used for mass assignment
    protected $fillable = ['title', 'description', 'price', 'image'];
}

