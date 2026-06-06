<?php
// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || $user->role_id != 3) {
            abort(403, 'Доступ запрещён. Только для администраторов.');
        }
    }

    // Список товаров
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = Product::with('category');

        // Поиск по названию
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // Фильтр по категории
        if ($request->filled('category_id')) {
            $query->where('id_category', $request->category_id);
        }

        // Фильтр по доступности
        if ($request->filled('is_available') && $request->is_available != '') {
            $query->where('is_available', $request->is_available);
        }

        $products = $query->orderBy('product_id', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    // Форма создания
    public function create()
    {
        $this->checkAdmin();
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Сохранение товара
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:100',
            'id_category' => 'required|exists:Categories,category_id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'nullable|string|max:20',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $filename, 'public');
            $imageUrl = '/storage/' . $path;
        }

        Product::create([
            'name' => $request->name,
            'id_category' => $request->id_category,
            'description' => $request->description,
            'price' => $request->price,
            'weight' => $request->weight,
            'is_available' => $request->has('is_available'),
            'image_url' => $imageUrl,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Товар добавлен!');
    }

    // Форма редактирования
    public function edit($id)
    {
        $this->checkAdmin();
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Обновление товара
    // Обновление товара
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'id_category' => 'required|exists:Categories,category_id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'weight' => 'nullable|string|max:20',
            'is_available' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Обработка изображения
        if ($request->hasFile('image')) {
            // Удаляем старую картинку если есть
            if ($product->image_url && file_exists(public_path($product->image_url))) {
                unlink(public_path($product->image_url));
            }

            $file = $request->file('image');
            $filename = time() . '_' . \Illuminate\Support\Str::slug($request->name) . '.' . $file->getClientOriginalExtension();

            // Сохраняем в storage/app/public/products
            $path = $file->storeAs('products', $filename, 'public');
            $imageUrl = '/storage/' . $path;

            $product->image_url = $imageUrl;
        }

        // Обновляем остальные поля
        $product->name = $request->name;
        $product->id_category = $request->id_category;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->weight = $request->weight;
        $product->is_available = $request->has('is_available');
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Товар обновлён!');
    }

    // Переключение видимости (скрыть/показать)
    public function toggle($id)
    {
        $this->checkAdmin();

        $product = Product::findOrFail($id);
        $product->is_available = !$product->is_available;
        $product->save();

        $status = $product->is_available ? 'показан' : 'скрыт';
        return redirect()->back()->with('success', "Товар '{$product->name}' {$status}");
    }
}
