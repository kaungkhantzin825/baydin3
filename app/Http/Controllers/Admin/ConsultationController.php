<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toask;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateConsultationRequest;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $query = Toask::with(['user', 'astrologer', 'category']);
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by astrologer
        if ($request->filled('astrologer_id')) {
            $query->where('astrologers_id', $request->astrologer_id);
        }
        
        // Search
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $consultations = $query->latest()->paginate(15);
        $astrologers = User::where('role', 'astrology')->get();
        
        return view('admin.consultations.index', compact('consultations', 'astrologers'));
    }
    
    public function show(Toask $consultation)
    {
        $consultation->load(['user', 'astrologer', 'category']);
        return view('admin.consultations.show', compact('consultation'));
    }
    
    public function edit(Toask $consultation)
    {
        $consultation->load(['user', 'astrologer', 'category']);
        $astrologers = User::where('role', 'astrology')->get();
        $categories = Category::where('status', 'active')->get();
        $customers = User::where('role', 'customer')->get();
        
        return view('admin.consultations.edit', compact('consultation', 'astrologers', 'categories', 'customers'));
    }
    
    public function update(UpdateConsultationRequest $request, Toask $consultation)
    {
        // Verify astrologer role if assigned
        if ($request->astrologers_id) {
            $astrologer = User::findOrFail($request->astrologers_id);
            if ($astrologer->role !== 'astrology') {
                return back()->with('error', 'Selected user is not an astrologer!');
            }
        }
        
        // Verify customer role
        $customer = User::findOrFail($request->user_id);
        if ($customer->role !== 'customer') {
            return back()->with('error', 'Selected user is not a customer!');
        }
        
        $data = $request->except(['photos', 'voice', 'video']);
        
        // Handle file uploads
        if ($request->hasFile('photos')) {
            // Delete old file if exists
            if ($consultation->photos && \Storage::disk('public')->exists($consultation->photos)) {
                \Storage::disk('public')->delete($consultation->photos);
            }
            $photoPath = $request->file('photos')->store('consultation_photos', 'public');
            $data['photos'] = $photoPath;
        }
        
        if ($request->hasFile('voice')) {
            // Delete old file if exists
            if ($consultation->voice && \Storage::disk('public')->exists($consultation->voice)) {
                \Storage::disk('public')->delete($consultation->voice);
            }
            $voicePath = $request->file('voice')->store('consultation_voices', 'public');
            $data['voice'] = $voicePath;
        }
        
        if ($request->hasFile('video')) {
            // Delete old file if exists
            if ($consultation->video && \Storage::disk('public')->exists($consultation->video)) {
                \Storage::disk('public')->delete($consultation->video);
            }
            $videoPath = $request->file('video')->store('consultation_videos', 'public');
            $data['video'] = $videoPath;
        }
        
        $consultation->update($data);
        
        return redirect()->route('admin.consultations.index')
            ->with('success', 'Consultation updated successfully!');
    }
    
    public function updateStatus(Request $request, Toask $consultation)
    {
        $request->validate([
            'status' => 'required|in:pending,in progress,completed'
        ]);
        
        $consultation->update(['status' => $request->status]);
        
        return back()->with('success', 'Consultation status updated successfully!');
    }
    
    public function assignAstrologer(Request $request, Toask $consultation)
    {
        $request->validate([
            'astrologers_id' => 'required|exists:users,id'
        ]);
        
        $astrologer = User::findOrFail($request->astrologers_id);
        
        if ($astrologer->role !== 'astrology') {
            return back()->with('error', 'Selected user is not an astrologer!');
        }
        
        $consultation->update(['astrologers_id' => $request->astrologers_id]);
        
        return back()->with('success', 'Astrologer assigned successfully!');
    }
    
    public function destroy(Toask $consultation)
    {
        $consultation->delete();
        
        return redirect()->route('admin.consultations.index')
            ->with('success', 'Consultation deleted successfully!');
    }
}