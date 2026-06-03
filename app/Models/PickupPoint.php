<?php
// app/Models/PickupPoint.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    protected $table = 'PickupPoints';
    protected $primaryKey = 'point_id';
    public $timestamps = false;

    protected $fillable = [
        'address',
        'phone',
        'working_hours',
    ];

    // Связь с заказами (у одного ПВЗ много заказов)
    public function orders()
    {
        return $this->hasMany(Order::class, 'pickup_point_id', 'point_id');
    }

    // Связь с пользователями (если у пользователя есть привязанный ПВЗ)
    public function users()
    {
        return $this->hasMany(User::class, 'pickup_point_id', 'point_id');
    }
}
