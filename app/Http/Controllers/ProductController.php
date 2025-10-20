<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $query = Product::with(['category', 'supplier', 'media'])
            ->orderBy('id', 'ASC');

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"))
                ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
        }

        $products = $query->get();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
            'buy_price'   => 'required|numeric|min:0',
            'sale_price'  => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Handle image upload (optional)
        $media_id = null;
        if ($request->hasFile('photo')) {
    $file = $request->file('photo');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $file->move(public_path('uploads/products'), $fileName);

    $media = Media::create([
        'file_name' => $fileName,
        'file_type' => $file->getClientMimeType(),
    ]);

    $media_id = $media->id;
}

       Product::create([
    'name'        => $data['name'],
    'category_id' => $data['category_id'],  // ✅ make sure this line is present
    'supplier_id' => $data['supplier_id'] ?? null,
    'buy_price'   => $data['buy_price'],
    'sale_price'  => $data['sale_price'],
    'quantity'    => $data['quantity'],
    'media_id'    => $media_id,
    'date'        => now('Asia/Manila'),    // ✅ real time
]);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers = Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
            'buy_price'   => 'required|numeric|min:0',
            'sale_price'  => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ Handle new image upload (optional)
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $fileName);

            $media = Media::create([
                'file_name' => $fileName,
                'type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            $data['media_id'] = $media->id;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->media && file_exists(public_path('uploads/products/' . $product->media->file_name))) {
            unlink(public_path('uploads/products/' . $product->media->file_name));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Optional: redirect show() to edit view
     */
    public function show(Product $product)
    {
        return redirect()->route('products.edit', $product->id);
    }
}
