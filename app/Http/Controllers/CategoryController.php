<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ✅ Show all categories
    public function index()
    {
        $items = Category::all();
        return view('categories.index', compact('items'));
    }

    // ✅ Show add form (optional if using the same page)
    public function create()
    {
        return view('categories.create');
    }

    // ✅ Save new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create($request->only('name'));

        return redirect()->route('categories.index')->with('success', 'Successfully added new category.');
    }

    // ✅ Edit page
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // ✅ Update existing category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category->update($request->only('name'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    // ✅ Delete category
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
