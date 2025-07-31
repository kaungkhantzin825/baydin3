<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FreeBaydin;
use Illuminate\Http\Request;

class FreeBaydinController extends Controller
{
    public function index(Request $request)
    {
        $query = FreeBaydin::query();
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        $consultations = $query->latest()->paginate(15);
        
        return view('admin.free-consultations.index', compact('consultations'));
    }
    
    public function create()
    {
        return view('admin.free-consultations.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);
        
        FreeBaydin::create($request->all());
        
        return redirect()->route('admin.free-consultations.index')
            ->with('success', 'Free consultation created successfully!');
    }
    
    public function show(FreeBaydin $consultation)
    {
        return view('admin.free-consultations.show', compact('consultation'));
    }
    
    public function edit(FreeBaydin $consultation)
    {
        return view('admin.free-consultations.edit', compact('consultation'));
    }
    
    public function update(Request $request, FreeBaydin $consultation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'status' => 'required|in:active,inactive'
        ]);
        
        $consultation->update($request->all());
        
        return redirect()->route('admin.free-consultations.index')
            ->with('success', 'Free consultation updated successfully!');
    }
    
    public function destroy(FreeBaydin $consultation)
    {
        $consultation->delete();
        
        return redirect()->route('admin.free-consultations.index')
            ->with('success', 'Free consultation deleted successfully!');
    }
    
    public function toggleStatus(FreeBaydin $consultation)
    {
        $consultation->update([
            'status' => $consultation->status === 'active' ? 'inactive' : 'active'
        ]);
        
        return back()->with('success', 'Consultation status updated successfully!');
    }
}