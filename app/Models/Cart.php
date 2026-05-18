<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'Carts';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = ['user_id', 'id_product', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'product_id');
    }
}
