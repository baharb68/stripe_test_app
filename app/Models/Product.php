<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
      'name',
      'slug',
      'stripe_id',
      'price',
      'description'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function get_product_price($id){
        return self::find($id)->price . '$';
    }

    public static function get_product_name($id){
        return self::find($id)->name;
    }
}
