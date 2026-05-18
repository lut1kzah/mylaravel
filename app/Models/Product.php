<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'Products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = ['id_category', 'name', 'description', 'price', 'is_available', 'image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'category_id');
    }
}
