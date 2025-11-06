<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    /**
     * 🧾 Display a listing of the products.
     */
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $products = Product::with(['category', 'supplier', 'media'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('category', fn($q) => $q->where('name', 'like', "%{$search}%"))
                      ->orWhereHas('supplier', fn($q) => $q->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('id', 'ASC')
            ->get();

        return view('products.index', compact('products', 'search'));
    }

    /**
     * ➕ Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * 💾 Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'buy_price'   => 'required|numeric|min:0',
            'sale_price'  => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 🖼️ Handle image upload
        $media_id = null;
        if ($request->hasFile('photo')) {
            $file     = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();

            $file->move(public_path('uploads/products'), $fileName);

            $media = Media::create([
                'file_name' => $fileName,
                'file_type' => $fileType,
                'size'      => $fileSize,
            ]);

            $media_id = $media->id;
        }

        // 👤 Get admin name
        $adminName = Auth::check() ? Auth::user()->name : 'Unknown';

        // 🗃️ Save Product
        Product::create([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,
            'buy_price'   => $validated['buy_price'],
            'sale_price'  => $validated['sale_price'],
            'quantity'    => $validated['quantity'],
            'media_id'    => $media_id,
            'date'        => now('Asia/Manila'),
            'admin_name'  => $adminName,
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', '✅ Product created successfully.');
    }

    /**
     * ✏️ Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * ♻️ Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id',
            'buy_price'   => 'required|numeric|min:0',
            'sale_price'  => 'required|numeric|min:0',
            'quantity'    => 'required|integer|min:0',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $media_id = $product->media_id;

        // 🖼️ Replace image if new one is uploaded
        if ($request->hasFile('photo')) {
            if ($product->media && file_exists(public_path('uploads/products/' . $product->media->file_name))) {
                unlink(public_path('uploads/products/' . $product->media->file_name));
                $product->media->delete();
            }

            $file     = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileType = $file->getClientMimeType();
            $fileSize = $file->getSize();

            $file->move(public_path('uploads/products'), $fileName);

            $media = Media::create([
                'file_name' => $fileName,
                'file_type' => $fileType,
                'size'      => $fileSize,
            ]);

            $media_id = $media->id;
        }

        // 👤 Update admin name
        $adminName = Auth::check() ? Auth::user()->name : 'Unknown';

        // 💾 Update Product
        $product->update([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,
            'buy_price'   => $validated['buy_price'],
            'sale_price'  => $validated['sale_price'],
            'quantity'    => $validated['quantity'],
            'media_id'    => $media_id,
            'admin_name'  => $adminName,
            'date'        => now('Asia/Manila'),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', '✅ Product updated successfully.');
    }

    /**
     * 🔄 Redirect show() to edit view.
     */
    public function show(Product $product)
    {
        return redirect()->route('products.edit', $product->id);
    }

    /**
     * 🗑️ Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->media && file_exists(public_path('uploads/products/' . $product->media->file_name))) {
            unlink(public_path('uploads/products/' . $product->media->file_name));
            $product->media->delete();
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', '✅ Product deleted successfully.');
    }
}
