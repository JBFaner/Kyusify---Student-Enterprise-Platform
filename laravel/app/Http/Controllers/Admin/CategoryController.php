<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('display_order')->get();
        return view('admin.content.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $validated['status'] = $request->has('status');
        $validated['display_order'] = Category::max('display_order') + 1;

        Category::create($validated);

        return redirect()->route('admin.content.categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
        ]);

        $validated['status'] = $request->has('status');

        $category->update($validated);

        return redirect()->route('admin.content.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.content.categories.index')->with('success', 'Category deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $orderedIds = $request->input('ordered_ids', []);
        
        foreach ($orderedIds as $index => $id) {
            Category::where('id', $id)->update(['display_order' => $index]);
        }

        return response()->json(['success' => true]);
    }
}
