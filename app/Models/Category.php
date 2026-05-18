<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'Categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = ['category_name'];

    public function products()
    {
        return $this->hasMany(Product::class, 'id_category', 'category_id');
    }
}
