<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    /**
     * Time allowed after the deadline for the browser's automatic submission to reach the server.
     */
    const SUBMIT_GRACE_SECONDS = 30;

    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'completed_at',
        'score_objective',
        'score_essay',
        'total_score',
        'tab_switch_count',
        'force_submitted',
        'status',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'score_objective' => 'decimal:2',
        'score_essay' => 'decimal:2',
        'total_score' => 'decimal:2',
        'tab_switch_count' => 'integer',
        'force_submitted' => 'boolean',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(ExamAnswer::class);
    }

    /**
     * The moment this attempt must end: the exam's duration counted from the start
     * of the attempt, or the exam's end time, whichever comes first.
     */
    public function deadline(Exam $exam): ?Carbon
    {
        $limits = array_filter([
            $exam->duration_minutes ? $this->started_at->copy()->addMinutes($exam->duration_minutes) : null,
            $exam->end_time,
        ]);

        return $limits ? min($limits) : null;
    }

    /**
     * True when the deadline has passed, allowing an optional grace period for
     * the browser's final submission to arrive.
     */
    public function isPastDeadline(Exam $exam, int $graceSeconds = 0): bool
    {
        $deadline = $this->deadline($exam);

        return $deadline !== null && now()->isAfter($deadline->copy()->addSeconds($graceSeconds));
    }
}
