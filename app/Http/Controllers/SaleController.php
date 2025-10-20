<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class SaleController extends Controller
{
    /**
     * 🧾 Show all sales
     */
    public function index()
    {
        $sales = Sale::with('product')->latest('date')->get();
        return view('sales.index', compact('sales'));
    }

    /**
     * 🪟 Load Add Sale modal content
     */
    public function create()
    {
        return view('sales.partials.add_modal');
    }

    /**
     * 🔍 AJAX product search
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $products = Product::where('name', 'like', "%{$query}%")
            ->orderBy('name', 'asc')
            ->take(10)
            ->get(['id', 'name', 'sale_price', 'quantity']);

        return response()->json($products);
    }

    /**
     * 🧩 Get selected product info (for row)
     */
    public function getProduct(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $html = view('sales.partials.product_row', compact('product'))->render();
        return response()->json(['html' => $html]);
    }

    /**
     * 💾 Store new sale (AJAX)
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        // ⚠️ Prevent selling more than in stock
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'error' => 'Not enough stock available.'
            ]);
        }

        $total = $product->sale_price * $request->quantity;

        // ✅ Save sale
        $sale = Sale::create([
            'product_id' => $product->id,
            'qty' => $request->quantity,
            'price' => $total,
            'date' => Carbon::now()->toDateString(),
        ]);

        // ✅ Deduct stock
        $product->decrement('quantity', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => '✅ Sale successfully added!',
            'sale' => [
                'item' => $product->name,
                'price' => $product->sale_price,
                'qty' => $request->quantity,
                'total' => $total,
                'date' => now()->format('Y-m-d'),
            ]
        ]);
    }

    /**
     * 🗑 Delete a sale (optional)
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $product = $sale->product;

        // ✅ Return stock when sale deleted
        if ($product) {
            $product->increment('quantity', $sale->qty);
        }

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}
