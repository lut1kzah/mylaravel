<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BonusTransaction extends Model
{
    protected $table = 'Bonus_transactions';
    protected $primaryKey = 'transaction_id';
    public $timestamps = false;

    protected $fillable = ['id_user', 'id_order', 'amount', 'type', 'created_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id_order', 'order_id');
    }
}
