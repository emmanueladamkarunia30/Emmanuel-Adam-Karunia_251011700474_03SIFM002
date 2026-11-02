<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // DASHBOARD
    public function index(Request $request)
    {
        $query = Project::query();

        // FILTER KATEGORI
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('teknologi', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $projects = $query->latest()->paginate(15);

        return view('admin.index', compact('projects'));
    }

    // FORM TAMBAH DATA
    public function create()
    {
        return view('admin.form');
    }

    // SIMPAN DATA
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|in:Hardware,Software',
            'description' => 'nullable|string',
            'teknologi' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('projects', 'public');
        }

        Project::create($validated);

        return redirect()
            ->route('admin.home')
            ->with('success', 'Data berhasil ditambahkan!');
    }

    // FORM EDIT DATA
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        return view('admin.form', compact('project'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'kategori' => 'required|in:Hardware,Software',
            'description' => 'nullable|string',
            'teknologi' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {

            if ($project->image && str_starts_with($project->image, 'projects/')) {
                Storage::disk('public')->delete($project->image);
            }

            $validated['image'] = $request->file('image')
                ->store('projects', 'public');
        }

        $project->update($validated);

        return redirect()
            ->route('admin.home')
            ->with('success', 'Data berhasil diperbarui!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->image && str_starts_with($project->image, 'projects/')) {
            Storage::disk('public')->delete($project->image);
        }

        $project->delete();

        return redirect()
            ->route('admin.home')
            ->with('success', 'Data berhasil dihapus!');
    }
}