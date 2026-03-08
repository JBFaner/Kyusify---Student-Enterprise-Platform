<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    public function index(Request $request)
    {
        $query = Enterprise::query()->with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enterprises = $query->latest()->paginate(10);

        return view('admin.enterprise-management.index', compact('enterprises'));
    }

    public function show(Enterprise $enterprise)
    {
        $enterprise->load('user');
        return view('admin.enterprise-management.show', compact('enterprise'));
    }

    public function edit(Enterprise $enterprise)
    {
        return view('admin.enterprise-management.edit', compact('enterprise'));
    }

    public function update(Request $request, Enterprise $enterprise)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
            'is_student_verified' => 'boolean',
        ]);

        // checkbox boolean handling
        $validated['is_student_verified'] = $request->has('is_student_verified');

        $enterprise->update($validated);

        return redirect()->route('admin.enterprises.index')->with('success', 'Enterprise updated successfully.');
    }

    public function destroy(Enterprise $enterprise)
    {
        $enterprise->delete();
        return redirect()->route('admin.enterprises.index')->with('success', 'Enterprise deleted successfully.');
    }
}
