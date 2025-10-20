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
     * Display a listing of the products.
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
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created product in storage.
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

        // ✅ Handle image upload (optional)
        $media_id = null;
        if ($request->hasFile('photo')) {
            $file     = $request->file('photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/products'), $fileName);

            $media = Media::create([
                'file_name' => $fileName,
                'file_type' => $file->getClientMimeType(),
            ]);

            $media_id = $media->id;
        }

        // ✅ Determine admin name (from logged-in user or fallback)
        $adminName = Auth::check() ? Auth::user()->name : 'Unknown';

        Product::create([
            'name'        => $validated['name'],
            'category_id' => $validated['category_id'],
            'supplier_id' => $validated['supplier_id'] ?? null,
            'buy_price'   => $validated['buy_price'],
            'sale_price'  => $validated['sale_price'],
            'quantity'    => $validated['quantity'],
            'media_id'    => $media_id,
            'date'        => now('Asia/Manila'),
            'admin_name'  => $adminName, // ✅ Save admin name
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', '✅ Product created successfully.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $suppliers  = Supplier::all();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

   /**
 * Update the specified product in storage.
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

    // ✅ Keep old media ID by default
    $validated['media_id'] = $product->media_id;

// ✅ Handle image replacement if new file is uploaded
if ($request->hasFile('photo')) {
    if ($product->media && file_exists(public_path('uploads/products/' . $product->media->file_name))) {
        unlink(public_path('uploads/products/' . $product->media->file_name));
    }

    $file     = $request->file('photo');
    $fileName = time() . '_' . $file->getClientOriginalName();
    $fileSize = $file->getSize(); // ✅ Get size BEFORE moving
    $fileType = $file->getClientMimeType();

    $file->move(public_path('uploads/products'), $fileName);

    $media = Media::create([
        'file_name' => $fileName,
        'file_type' => $fileType,
        'size'      => $fileSize,
    ]);

    $validated['media_id'] = $media->id;
}

    // 👤 Update admin name when updated
    $validated['admin_name'] = Auth::check() ? Auth::user()->name : 'Unknown';

    // ✅ Save updates
    $product->update($validated);

    return redirect()
        ->route('products.index')
        ->with('success', '✅ Product updated successfully.');
}


    /**
     * Redirect show() to edit view
     */
    public function show(Product $product)
    {
        return redirect()->route('products.edit', $product->id);
    }
}
