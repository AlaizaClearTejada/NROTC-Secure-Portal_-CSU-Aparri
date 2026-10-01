@extends('layouts.app')

@section('title', 'Taking Exam: ' . $exam->title)

{{-- We remove standard sidebar and header for the exam interface to reduce distractions --}}
@section('sidebar') @endsection
@section('topbar') @endsection

@section('content')
<div x-data="examRoom({{ $exam->id }}, {{ $exam->duration_minutes ? $attempt->started_at->addMinutes($exam->duration_minutes)->timestamp : 'null' }}, {{ $exam->auto_submit_on_tab_switch ? 'true' : 'false' }}, {{ $exam->prevent_back_navigation ? 'true' : 'false' }})" class="min-h-screen bg-slate-100 flex flex-col fixed inset-0 z-50">
    
    {{-- Exam Header (Fixed top) --}}
    <header class="bg-blue-800 text-white px-6 py-4 shadow-md shrink-0 flex items-center justify-between z-10">
        <div>
            <h1 class="text-xl font-black">{{ $exam->title }}</h1>
            <p class="text-sm text-blue-200">{{ $exam->subject ?? 'General' }}</p>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex flex-col items-end">
                <span class="text-xs text-blue-200 uppercase font-bold tracking-wider">Status</span>
                <span class="text-sm font-bold flex items-center gap-2" :class="saveStatus === 'Saving...' ? 'text-yellow-300' : (saveStatus === 'Saved' ? 'text-green-300' : '')">
                    <svg x-show="saveStatus === 'Saving...'" class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="saveStatus"></span>
                </span>
            </div>
            
            @if($exam->duration_minutes)
                <div class="bg-blue-900 rounded-lg px-4 py-2 border border-blue-700/50 flex flex-col items-center min-w-[120px]">
                    <span class="text-[10px] text-blue-300 uppercase font-bold tracking-wider">Time Remaining</span>
                    <span class="text-xl font-black font-mono tracking-wider" :class="timeWarning ? 'text-red-400 animate-pulse' : 'text-white'" x-text="formattedTime">--:--:--</span>
                </div>
            @endif

            <button type="button" @click="submitExam(true)" class="px-6 py-2 bg-green-500 hover:bg-green-600 text-white font-black rounded-lg shadow-lg shadow-green-900/20 transition-colors">
                Finish Exam
            </button>
        </div>
    </header>

    {{-- Exam Body --}}
    <main class="flex-1 overflow-y-auto p-6 scroll-smooth" id="exam-container">
        <div class="max-w-4xl mx-auto space-y-8 pb-24">
            
            <div x-show="violationWarning" style="display: none;" class="bg-red-100 border-l-4 border-red-500 text-red-800 p-4 rounded shadow-sm mb-6 flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <div>
                    <h4 class="font-black">Security Warning</h4>
                    <p class="text-sm mt-1">You have left the exam tab. Multiple violations will result in automatic submission of your exam. <span class="font-bold">Violations: <span x-text="violationsCount"></span>/3</span></p>
                </div>
                <button @click="violationWarning = false" class="ml-auto text-red-500 hover:text-red-800 font-bold text-xs">Dismiss</button>
            </div>

            <form id="examForm" @submit.prevent="submitExam(true)">
                @foreach([1, 2] as $part)
                    @php $partQuestions = $exam->questions->where('part', $part); @endphp
                    @if($partQuestions->isNotEmpty())
                        @if($exam->has_part_two)
                            <div class="mb-6 pb-2 border-b-2 border-slate-300">
                                <h2 class="text-xl font-black text-slate-800">Part {{ $part }} @if($part === 1 && $exam->part_one_title) : {{ $exam->part_one_title }} @endif @if($part === 2 && $exam->part_two_title) : {{ $exam->part_two_title }} @endif</h2>
                            </div>
                        @endif

                        <div class="space-y-6">
                            @foreach($partQuestions as $q)
                                @php 
                                    // Find if answer exists
                                    $existingAnswer = $attempt->answers->where('exam_question_id', $q->id)->first();
                                    $val = $existingAnswer ? $existingAnswer->answer_text : null;
                                    $qNum = $loop->iteration;
                                @endphp
                                <div class="card bg-white rounded-xl shadow-sm border border-slate-200 p-6" id="q_{{ $q->id }}">
                                    <div class="flex gap-4">
                                        <div class="shrink-0 pt-1">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-black">{{ $qNum }}</span>
                                        </div>
                                        <div class="flex-1 space-y-4">
                                            <div>
                                                <p class="text-lg font-medium text-slate-800">{{ $q->question_text }}</p>
                                                <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">{{ $q->points }} Points</p>
                                            </div>

                                            <div class="bg-slate-50 rounded-lg p-4 border border-slate-100">
                                                @if($q->type === 'multiple_choice')
                                                    <div class="space-y-3">
                                                        @foreach($q->options as $optIndex => $optText)
                                                            @php $optId = 'option_' . $optIndex; @endphp
                                                            <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors"
                                                                   :class="answers['{{ $q->id }}'] == '{{ $optId }}' ? 'border-blue-500 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-300'">
                                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $optId }}" x-model="answers['{{ $q->id }}']" @change="debouncedSave()" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                                                                <span class="font-bold text-slate-500 w-5">{{ chr(65 + $optIndex) }}.</span>
                                                                <span class="text-sm font-medium text-slate-700 select-none">{{ $optText }}</span>
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                @elseif($q->type === 'identification')
                                                    <input type="text" name="answers[{{ $q->id }}]" x-model="answers['{{ $q->id }}']" @input="debouncedSave()" placeholder="Type your answer here..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 font-medium">
                                                @elseif($q->type === 'enumeration')
                                                    <textarea name="answers[{{ $q->id }}]" x-model="answers['{{ $q->id }}']" @input="debouncedSave()" rows="3" placeholder="Enter answers separated by commas or on new lines..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 font-medium"></textarea>
                                                @elseif($q->type === 'essay')
                                                    <textarea name="answers[{{ $q->id }}]" x-model="answers['{{ $q->id }}']" @input="debouncedSave()" rows="5" placeholder="Write your essay here..." class="w-full px-4 py-3 bg-white border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 font-medium"></textarea>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </form>
            
            <div class="pt-8 text-center">
                <p class="text-sm text-slate-500 mb-4 font-bold">End of Examination</p>
                <button type="button" @click="submitExam(true)" class="px-10 py-4 bg-green-500 hover:bg-green-600 text-white font-black text-lg rounded-xl shadow-lg shadow-green-900/20 transition-all hover:-translate-y-1">
                    Submit Exam
                </button>
            </div>
            
        </div>
    </main>
</div>

{{-- Pass existing answers to Alpine --}}
<script>
    const existingAnswers = {!! json_encode($attempt->answers->mapWithKeys(function($a) {
        return [$a->exam_question_id => is_array($a->answer_text) ? $a->answer_text[0] : $a->answer_text];
    })) !!};
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('examRoom', (examId, endTimeTimestamp, strictTab, preventBack) => ({
            examId: examId,
            endTime: endTimeTimestamp,
            strictTab: strictTab,
            preventBack: preventBack,
            answers: typeof existingAnswers !== 'undefined' ? existingAnswers : {},
            saveStatus: 'Saved',
            saveTimeout: null,
            timeWarning: false,
            formattedTime: '--:--:--',
            violationWarning: false,
            violationsCount: 0,
            isSubmitting: false,

            init() {
                // Timer setup
                if (this.endTime) {
                    this.updateTimer();
                    setInterval(() => this.updateTimer(), 1000);
                }

                // Security: Prevent Back Navigation
                if (this.preventBack) {
                    history.pushState(null, null, location.href);
                    window.onpopstate = function () {
                        history.go(1);
                        alert("Back navigation is disabled during the exam.");
                    };
                }

                // Security: Tab Switching
                if (this.strictTab) {
                    document.addEventListener('visibilitychange', () => {
                        if (document.visibilityState === 'hidden' && !this.isSubmitting) {
                            this.handleTabSwitch();
                        }
                    });
                }
                
                // Warn before closing tab
                window.addEventListener('beforeunload', (e) => {
                    if (!this.isSubmitting) {
                        e.preventDefault();
                        e.returnValue = '';
                    }
                });
            },

            updateTimer() {
                if (!this.endTime) return;
                
                const now = Math.floor(Date.now() / 1000);
                let diff = this.endTime - now;
                
                if (diff <= 0) {
                    this.formattedTime = "00:00:00";
                    if (!this.isSubmitting) {
                        alert("Time's up! Your exam will now be submitted.");
                        this.submitExam(true, true); // final, forced
                    }
                    return;
                }

                if (diff < 300) { // 5 minutes remaining
                    this.timeWarning = true;
                }

                const h = Math.floor(diff / 3600).toString().padStart(2, '0');
                const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                const s = (diff % 60).toString().padStart(2, '0');
                
                this.formattedTime = `${h}:${m}:${s}`;
            },

            debouncedSave() {
                this.saveStatus = 'Unsaved changes';
                clearTimeout(this.saveTimeout);
                this.saveTimeout = setTimeout(() => {
                    this.submitExam(false);
                }, 2000); // Auto-save 2 seconds after last input
            },

            submitExam(isFinal = false, isForced = false) {
                if (isFinal && !isForced && !confirm("Are you sure you want to finish and submit the exam? You cannot change your answers after this.")) {
                    return;
                }

                if (isFinal) {
                    this.isSubmitting = true;
                    this.saveStatus = 'Submitting...';
                } else {
                    this.saveStatus = 'Saving...';
                }

                const payload = {
                    _token: '{{ csrf_token() }}',
                    is_final: isFinal ? 1 : 0,
                    is_forced: isForced ? 1 : 0,
                    answers: this.answers
                };

                fetch(`/cadet/exams/${this.examId}/submit`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (isFinal && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data.success) {
                        this.saveStatus = 'Saved';
                    }
                })
                .catch(err => {
                    console.error("Save error:", err);
                    if (!isFinal) this.saveStatus = 'Error saving';
                });
            },

            handleTabSwitch() {
                fetch(`/cadet/exams/${this.examId}/tab-switch`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.violationsCount = data.count;
                        this.violationWarning = true;
                        
                        if (this.violationsCount >= 3) {
                            alert("You have exceeded the maximum allowed tab switches. Your exam will now be automatically submitted.");
                            this.submitExam(true, true);
                        }
                    }
                });
            }
        }));
    });
</script>
@endsection
