<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        // Получаем все категории с их товарами
        $categories = Category::with('products')->get();

        return view('menu', compact('categories'));
    }
}
