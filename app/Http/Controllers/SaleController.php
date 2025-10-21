<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
     * ✏️ Load Edit Sale modal content
     */
    public function edit($id)
    {
        $sale = Sale::with('product')->findOrFail($id);
        $products = Product::orderBy('name', 'asc')->get();
        return view('sales.partials.edit_modal', compact('sale', 'products'));
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
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // ⚠️ Prevent overselling
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'error'   => 'Not enough stock available.'
            ]);
        }

        $adminName = Auth::user()->name ?? 'System Admin';
        $saleDate = Carbon::now('Asia/Manila');
        $total = $product->sale_price * $request->quantity;

        DB::beginTransaction();
        try {
            // Create sale
            $sale = Sale::create([
                'product_id' => $product->id,
                'qty'        => $request->quantity,
                'price'      => $total,
                'date'       => $saleDate,
                'admin_name' => $adminName,
            ]);

            // Deduct stock
            $product->decrement('quantity', $request->quantity);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Sale successfully added!',
                'sale'    => [
                    'item'  => $product->name,
                    'price' => $product->sale_price,
                    'qty'   => $request->quantity,
                    'total' => $total,
                    'date'  => $sale->date->format('Y-m-d H:i:s'),
                    'admin' => $adminName,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error'   => '⚠️ Failed to add sale. Please try again.',
            ]);
        }
    }

    /**
     * ♻️ Update sale (AJAX)
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'price'      => 'required|numeric|min:0',
            'date'       => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $sale = Sale::findOrFail($id);
            $oldQty = $sale->qty;
            $product = Product::findOrFail($sale->product_id);

            // Restore old stock first
            $product->increment('quantity', $oldQty);

            // Get new product (in case changed)
            $newProduct = Product::findOrFail($request->product_id);

            // Check stock availability for new quantity
            if ($newProduct->quantity < $request->qty) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => 'Not enough stock for the selected product.',
                ]);
            }

            // Update sale record
            $sale->update([
                'product_id' => $newProduct->id,
                'qty' => $request->qty,
                'price' => $request->price,
                'date' => Carbon::parse($request->date),
                'admin_name' => Auth::user()->name ?? 'System Admin',
            ]);

            // Deduct new stock
            $newProduct->decrement('quantity', $request->qty);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => '✅ Sale updated successfully!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => '⚠️ Failed to update sale. Please try again.',
            ]);
        }
    }

    /**
     * 🗑 Delete a sale
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $product = $sale->product;

        if ($product) {
            $product->increment('quantity', $sale->qty);
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
