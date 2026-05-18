<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    protected $table = 'Promocodes';
    protected $primaryKey = 'promocode_id';
    public $timestamps = false;

    protected $fillable = ['code', 'discount_percent', 'valid_from', 'valid_until', 'min_order_sum', 'is_active'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'used_promocode_id', 'promocode_id');
    }
}
