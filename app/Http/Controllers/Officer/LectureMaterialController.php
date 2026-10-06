<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\LectureMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LectureMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = LectureMaterial::with('creator');

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%'.$request->subject.'%');
        }

        $materials = $query->latest()->paginate(15);

        return view('officer.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('officer.materials.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:20480', // 20MB max
            'subject' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:255',
            'availability' => 'required|in:all,ms1,ms2',
            'is_published' => 'boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('lecture_materials', 'public');
        $extension = strtolower($file->getClientOriginalExtension());

        // Normalize some common extensions
        $fileType = match ($extension) {
            'pdf' => 'pdf',
            'doc', 'docx' => 'docx',
            'ppt', 'pptx' => 'pptx',
            'xls', 'xlsx' => 'xlsx',
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            'mp4', 'webm' => 'video',
            default => 'other',
        };

        LectureMaterial::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $path,
            'file_type' => $fileType,
            'subject' => $request->subject,
            'topic' => $request->topic,
            'availability' => $request->availability,
            'is_published' => $request->boolean('is_published', true),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('officer.materials.index')->with('success', 'Lecture material uploaded successfully.');
    }

    public function edit(LectureMaterial $material)
    {
        return view('officer.materials.form', compact('material'));
    }

    public function update(Request $request, LectureMaterial $material)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'nullable|file|max:20480',
            'subject' => 'nullable|string|max:255',
            'topic' => 'nullable|string|max:255',
            'availability' => 'required|in:all,ms1,ms2',
            'is_published' => 'boolean',
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'subject' => $request->subject,
            'topic' => $request->topic,
            'availability' => $request->availability,
            'is_published' => $request->boolean('is_published', false),
        ];

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }

            $file = $request->file('file');
            $data['file_path'] = $file->store('lecture_materials', 'public');
            $extension = strtolower($file->getClientOriginalExtension());
            $data['file_type'] = match ($extension) {
                'pdf' => 'pdf',
                'doc', 'docx' => 'docx',
                'ppt', 'pptx' => 'pptx',
                'xls', 'xlsx' => 'xlsx',
                'jpg', 'jpeg', 'png', 'gif' => 'image',
                'mp4', 'webm' => 'video',
                default => 'other',
            };
        }

        $material->update($data);

        return redirect()->route('officer.materials.index')->with('success', 'Lecture material updated successfully.');
    }

    public function destroy(LectureMaterial $material)
    {
        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('officer.materials.index')->with('success', 'Lecture material deleted.');
    }
}
