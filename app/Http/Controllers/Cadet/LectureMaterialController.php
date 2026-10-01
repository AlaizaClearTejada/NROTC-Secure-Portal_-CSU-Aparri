<?php

namespace App\Http\Controllers\Cadet;

use App\Http\Controllers\Controller;
use App\Models\LectureMaterial;
use Illuminate\Http\Request;

class LectureMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = LectureMaterial::with('creator')->where('is_published', true);

        if ($request->filled('subject')) {
            $query->where('subject', 'like', '%' . $request->subject . '%');
        }

        // Ideally we would filter by the cadet's MS grade level if specified:
        // if (auth()->user()->ms_grade_level === 'MS1') ...

        $materials = $query->latest()->paginate(15);
        
        return view('cadet.materials.index', compact('materials'));
    }
}
