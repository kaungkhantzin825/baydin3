<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('toasks')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    
    public function create()
    {
        return view('admin.categories.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        $data = $request->all();
        $data['status_new'] = $data['status'];
        unset($data['status']);
        
        Category::create($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }
    
    public function show(Category $category)
    {
        $category->load(['toasks.user', 'toasks.astrologer']);
        return view('admin.categories.show', compact('category'));
    }
    
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'price' => 'required|numeric|min:0|max:999999.99',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive'
        ]);
        
        $data = $request->all();
        $data['status_new'] = $data['status'];
        unset($data['status']);
        
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }
    
    public function destroy(Category $category)
    {
        if ($category->toasks()->count() > 0) {
            return back()->with('error', 'Cannot delete category with existing consultations!');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
    
    public function toggleStatus(Category $category)
    {
        $newStatus = $category->status === 'active' ? 'inactive' : 'active';
        $category->update([
            'status_new' => $newStatus
        ]);
        
        return back()->with('success', 'Category status updated successfully!');
    }
}