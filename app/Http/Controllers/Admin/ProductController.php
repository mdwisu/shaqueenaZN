<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.form', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Create product
        Product::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(5),
            'description' => $request->description,
            'price' => $request->price,
            'cost_price' => $request->cost_price,
            'pricing_mode' => $request->pricing_mode ?? 'manual',
            'markup_percent' => $request->pricing_mode === 'auto' ? $request->markup_percent : null,
            'is_featured' => $request->has('is_featured') ? 1 : 0,
            'featured_start' => $request->featured_start,
            'featured_end' => $request->featured_end,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'discount_start' => $request->discount_start,
            'discount_end' => $request->discount_end,
            'stock_quantity' => $request->stock_quantity,
            'image' => $imagePath,
            'status' => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('admin.products')
            ->with('success', 'Product created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Check if the product belongs to the admin
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::all();
        return view('admin.products.form', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // Check if the product belongs to the admin
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = $imagePath;
        }

        // Update product
        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->slug = Str::slug($request->name) . '-' . Str::random(5);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->cost_price = $request->cost_price;
        $product->pricing_mode = $request->pricing_mode ?? 'manual';
        $product->markup_percent = $request->pricing_mode === 'auto' ? $request->markup_percent : null;
        $product->is_featured = $request->has('is_featured') ? 1 : 0;
        $product->featured_start = $request->featured_start;
        $product->featured_end = $request->featured_end;
        $product->discount_type = $request->discount_type;
        $product->discount_value = $request->discount_value;
        $product->discount_start = $request->discount_start;
        $product->discount_end = $request->discount_end;
        $product->stock_quantity = $request->stock_quantity;
        $product->status = $request->has('status') ? 1 : 0;
        $product->save();

        return redirect()->route('admin.products')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if the product belongs to the admin
        if ($product->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'Product deleted successfully');
    }
}
