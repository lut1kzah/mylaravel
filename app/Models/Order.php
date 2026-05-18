<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'Orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'order_date',
        'total_original_sum',
        'discount_sum',
        'final_sum',
        'used_promocode_id',
        'used_bonus_points',
        'earned_bonus_points',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'id_order', 'order_id');
    }

    public function promocode()
    {
        return $this->belongsTo(Promocode::class, 'used_promocode_id', 'promocode_id');
    }
}
