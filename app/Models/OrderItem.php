<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'Order_items';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;

    protected $fillable = ['id_order', 'id_product', 'product_name', 'price_per_item', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product', 'product_id');
    }
}
