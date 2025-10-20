<?php
namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(){ $items = Supplier::all(); return view('suppliers.index', compact('items')); }
    public function create(){ return view('suppliers.create'); }
    public function store(Request $r){ Supplier::create($r->only(['name','contact','email','address'])); return redirect()->route('suppliers.index'); }
    public function edit(Supplier $supplier){ return view('suppliers.edit', compact('supplier')); }
    public function update(Request $r, Supplier $supplier){ $supplier->update($r->only(['name','contact','email','address'])); return redirect()->route('suppliers.index'); }
    public function destroy(Supplier $supplier){ $supplier->delete(); return redirect()->route('suppliers.index'); }
}
