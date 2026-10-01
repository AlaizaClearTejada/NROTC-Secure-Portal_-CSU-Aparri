@extends('layouts.app')

@section('title', isset($material) ? 'Edit Material' : 'Upload Material')
@section('page-title', isset($material) ? 'Edit Material' : 'Upload Material')

@section('sidebar-nav')
    <a href="{{ route('officer.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Unit Oversight
    </a>
    <a href="{{ route('officer.materials.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        Lecture Materials
    </a>
@endsection

@section('content')
<div class="max-w-3xl space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('officer.materials.index') }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Materials
        </a>
    </div>

    <div class="card rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50">
            <h2 class="text-lg font-black text-slate-800">{{ isset($material) ? 'Edit Lecture Material' : 'Upload New Material' }}</h2>
            <p class="text-sm text-slate-500">Provide details and upload the file for cadets to access.</p>
        </div>

        <form method="POST" action="{{ isset($material) ? route('officer.materials.update', $material) : route('officer.materials.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            @if(isset($material)) @method('PUT') @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $material->title ?? '') }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                    @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">{{ old('description', $material->description ?? '') }}</textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', $material->subject ?? '') }}" placeholder="e.g. MS1, Navigation" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        @error('subject') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Topic</label>
                        <input type="text" name="topic" value="{{ old('topic', $material->topic ?? '') }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                        @error('topic') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Availability <span class="text-red-500">*</span></label>
                        <select name="availability" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent transition-all">
                            <option value="all" {{ old('availability', $material->availability ?? '') == 'all' ? 'selected' : '' }}>All Cadets</option>
                            <option value="ms1" {{ old('availability', $material->availability ?? '') == 'ms1' ? 'selected' : '' }}>MS1 Only</option>
                            <option value="ms2" {{ old('availability', $material->availability ?? '') == 'ms2' ? 'selected' : '' }}>MS2 Only</option>
                        </select>
                        @error('availability') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Status</label>
                        <div class="flex items-center gap-3 mt-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="radio" name="is_published" value="1" {{ old('is_published', $material->is_published ?? 1) == 1 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-600">
                                Published
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                                <input type="radio" name="is_published" value="0" {{ old('is_published', $material->is_published ?? 1) == 0 ? 'checked' : '' }} class="w-4 h-4 text-blue-600 focus:ring-blue-600">
                                Draft
                            </label>
                        </div>
                        @error('is_published') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center bg-slate-50 mt-4 relative">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <p class="text-sm font-bold text-slate-700">Choose a file or drag & drop it here</p>
                    <p class="text-xs text-slate-500 mt-1">PDF, DOCX, PPTX, XLSX, Images, MP4 (Max 20MB)</p>
                    <input type="file" name="file" {{ isset($material) ? '' : 'required' }} class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="file_input">
                    <div id="file_name" class="mt-3 text-sm font-bold text-blue-700">
                        @if(isset($material) && $material->file_path)
                            Current File: {{ basename($material->file_path) }}
                        @endif
                    </div>
                    @error('file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('officer.materials.index') }}" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-50 transition-colors">Cancel</a>
                <button type="submit" class="px-5 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors">
                    {{ isset($material) ? 'Save Changes' : 'Upload Material' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('file_input').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.getElementById('file_name').textContent = "Selected: " + e.target.files[0].name;
        }
    });
</script>
@endsection
