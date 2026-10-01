@extends('layouts.app')

@section('title', 'Manage Exam: ' . $exam->title)
@section('page-title', 'Manage Exam')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        Dashboard
    </a>
    <a href="{{ route('admin.exams.index') }}" class="sidebar-link active">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
        </svg>
        Examinations
    </a>
@endsection

@section('content')
<div class="max-w-5xl space-y-6 pb-20">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.exams.index') }}" class="text-xs font-semibold flex items-center gap-1 text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Exams
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 text-green-800 border border-green-200 rounded-lg p-4 text-sm font-semibold flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Exam Details Card --}}
    <div class="card rounded-xl overflow-hidden relative">
        <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
        <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between gap-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-800">{{ $exam->title }}</h1>
                    <p class="text-sm text-slate-500 mt-1 max-w-2xl">{{ $exam->description ?? 'No description provided.' }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 mt-5">
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $exam->duration_minutes ? $exam->duration_minutes . ' Minutes' : 'No Time Limit' }}
                        </span>
                        
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $exam->questions->count() }} Questions
                        </span>
                        
                        @if($exam->auto_submit_on_tab_switch)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 bg-red-50 border border-red-200 px-2.5 py-1 rounded">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Strict Security
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2 shrink-0">
                    <a href="{{ route('admin.exams.edit', $exam) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-100 text-slate-700 font-bold text-sm rounded-lg hover:bg-slate-200 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit Details
                    </a>
                    <a href="{{ route('admin.exams.results', $exam) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-50 text-indigo-700 font-bold text-sm rounded-lg hover:bg-indigo-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        View Results
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Questions Management --}}
    <div x-data="{
        showForm: {{ ($exam->questions->isEmpty() || $errors->any()) ? 'true' : 'false' }},
        qType: '{{ old('type', 'multiple_choice') }}',
        qPart: {{ old('part', 1) }},
        optionsCount: {{ is_array(old('options')) ? max(4, count(old('options'))) : 4 }},
        options: {{ json_encode(old('options', ['', '', '', ''])) }},
    }" class="space-y-4">
        
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-black text-slate-800">Exam Questions</h2>
            <button @click="showForm = true; window.scrollTo({ top: $refs.addForm.offsetTop, behavior: 'smooth' })" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Question
            </button>
        </div>

        {{-- Add Question Form --}}
        <div x-show="showForm" x-ref="addForm" style="display: none;" class="card rounded-xl border-t-4 border-t-blue-600 bg-slate-50 p-6 shadow-lg mb-8">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-base font-black text-slate-800">Add New Question</h3>
                <button @click="showForm = false" class="text-slate-400 hover:text-slate-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <div class="font-bold mb-1">Please fix the following errors:</div>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.exams.questions.store', $exam) }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Question Type</label>
                        <select name="type" x-model="qType" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="multiple_choice">Multiple Choice</option>
                            <option value="identification">Identification</option>
                            <option value="enumeration">Enumeration</option>
                            <option value="essay">Essay</option>
                        </select>
                    </div>
                    @if($exam->has_part_two)
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Part</label>
                            <select name="part" x-model="qPart" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                                <option value="1">Part 1</option>
                                <option value="2">Part 2</option>
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="part" value="1">
                    @endif
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Points</label>
                        <input type="number" name="points" value="1" min="1" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Question Text</label>
                    <textarea name="question_text" rows="2" required placeholder="Enter the question here..." class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('question_text') }}</textarea>
                </div>

                {{-- Dynamic Inputs based on Question Type --}}
                <div class="bg-white p-4 rounded-lg border border-slate-200">
                    
                    {{-- Multiple Choice Setup --}}
                    <div x-show="qType === 'multiple_choice'" class="space-y-4">
                        <p class="text-xs font-bold text-slate-600 uppercase tracking-wider">Choices & Correct Answer</p>
                        
                        <div class="space-y-3">
                            <template x-for="(opt, index) in optionsCount" :key="index">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="correct_answer_mc" :value="'option_' + index" :disabled="qType !== 'multiple_choice'" class="w-4 h-4 text-blue-600 focus:ring-blue-600" title="Mark as correct answer">
                                    <span class="text-sm font-bold text-slate-500 w-6" x-text="String.fromCharCode(65 + index) + '.'"></span>
                                    <input type="text" :name="'options[' + index + ']'" placeholder="Enter choice text" x-model="options[index]" :disabled="qType !== 'multiple_choice'" class="flex-1 px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                                </div>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="if(optionsCount < 10) { optionsCount++; options.push(''); }" class="text-xs font-bold text-blue-600 hover:text-blue-800">+ Add Option</button>
                            <button type="button" @click="if(optionsCount > 2) { optionsCount--; options.pop(); }" class="text-xs font-bold text-red-600 hover:text-red-800 ml-4">- Remove Option</button>
                        </div>
                    </div>

                    {{-- Identification Setup --}}
                    <div x-show="qType === 'identification'" style="display: none;">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Correct Answer</label>
                        <input type="text" name="correct_answer_ident" value="{{ old('correct_answer_ident') }}" :disabled="qType !== 'identification'" placeholder="e.g. George Washington" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <p class="text-[11px] text-slate-500 mt-1">Cadets must type this exact text (case-insensitive checking recommended in grading).</p>
                    </div>

                    {{-- Enumeration Setup --}}
                    <div x-show="qType === 'enumeration'" style="display: none;">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Correct Answers (Comma-separated)</label>
                        <textarea name="correct_answers_enum" rows="2" :disabled="qType !== 'enumeration'" placeholder="Item 1, Item 2, Item 3" class="w-full px-3 py-2 border border-slate-200 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-600">{{ old('correct_answers_enum') }}</textarea>
                        <p class="text-[11px] text-slate-500 mt-1">Separate the required items with commas. The system will provide multiple text boxes for the cadet.</p>
                    </div>

                    {{-- Essay Setup --}}
                    <div x-show="qType === 'essay'" style="display: none;">
                        <div class="bg-blue-50 text-blue-800 rounded-lg p-3 text-sm flex items-start gap-2">
                            <svg class="w-5 h-5 shrink-0 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p><strong>Manual Grading Required.</strong> Essay questions cannot be auto-graded. You will need to manually review the cadet's submission and assign points up to the maximum specified above.</p>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 pt-3">
                    <input type="hidden" name="order_index" value="{{ $exam->questions->count() + 1 }}">
                    <button type="button" @click="showForm = false" class="px-4 py-2 text-slate-600 font-bold text-sm hover:bg-slate-200 rounded-lg transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-blue-700 text-white font-bold text-sm rounded-lg hover:bg-blue-800 transition-colors shadow-sm">Save Question</button>
                </div>
            </form>
        </div>

        {{-- Existing Questions List --}}
        @if($exam->questions->isEmpty())
            <div class="border-2 border-dashed border-slate-200 rounded-xl p-12 text-center text-slate-500">
                <svg class="w-8 h-8 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                <p class="font-semibold text-slate-600">No questions added yet.</p>
                <button @click="showForm = true" class="mt-2 text-sm font-bold text-blue-600 hover:text-blue-800">Add the first question</button>
            </div>
        @else
            @foreach([1, 2] as $part)
                @php $partQuestions = $exam->questions->where('part', $part); @endphp
                @if($partQuestions->isNotEmpty())
                    @if($exam->has_part_two)
                        <h3 class="font-black text-slate-800 mt-6 mb-2 border-b border-slate-200 pb-2">
                            Part {{ $part }} 
                            @if($part === 1 && $exam->part_one_title) : <span class="font-bold text-slate-600">{{ $exam->part_one_title }}</span> @endif
                            @if($part === 2 && $exam->part_two_title) : <span class="font-bold text-slate-600">{{ $exam->part_two_title }}</span> @endif
                        </h3>
                    @endif
                    
                    <div class="space-y-3">
                        @foreach($partQuestions as $index => $q)
                            <div class="card bg-white border border-slate-200 rounded-lg p-5 flex flex-col md:flex-row gap-4 justify-between items-start group">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-600 text-xs font-black">{{ $loop->iteration }}</span>
                                        <span class="text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wider
                                            {{ $q->type === 'multiple_choice' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $q->type === 'identification' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $q->type === 'enumeration' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $q->type === 'essay' ? 'bg-orange-100 text-orange-700' : '' }}
                                        ">
                                            {{ str_replace('_', ' ', $q->type) }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-400">&bull; {{ $q->points }} pt(s)</span>
                                    </div>
                                    <p class="text-slate-800 font-medium pl-8">{{ $q->question_text }}</p>
                                    
                                    <div class="pl-8 mt-3 text-sm">
                                        @if($q->type === 'multiple_choice')
                                            <ul class="space-y-1">
                                                @foreach($q->options as $optIndex => $optText)
                                                    @php $isCorrect = in_array('option_' . $optIndex, $q->correct_answers ?? []); @endphp
                                                    <li class="flex items-center gap-2 {{ $isCorrect ? 'text-green-700 font-bold' : 'text-slate-500' }}">
                                                        <span class="w-4 h-4 rounded-full border flex items-center justify-center text-[9px] {{ $isCorrect ? 'border-green-500 bg-green-100' : 'border-slate-300' }}">
                                                            {{ chr(65 + $optIndex) }}
                                                        </span>
                                                        {{ $optText }}
                                                        @if($isCorrect) <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif($q->type === 'identification' || $q->type === 'enumeration')
                                            <p class="text-green-700 font-bold flex items-start gap-1.5">
                                                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Answers: {{ implode(', ', $q->correct_answers ?? []) }}</span>
                                            </p>
                                        @elseif($q->type === 'essay')
                                            <p class="text-orange-600 font-bold text-xs">Manual review required.</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center md:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="POST" action="{{ route('admin.exams.questions.destroy', [$exam, $q]) }}" onsubmit="return confirm('Are you sure you want to delete this question?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Delete Question">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
@endsection
