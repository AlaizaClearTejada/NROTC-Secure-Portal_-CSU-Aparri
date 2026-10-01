<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamQuestion extends Model
{
    protected $fillable = [
        'exam_id',
        'part',
        'question_text',
        'type',
        'options',
        'correct_answers',
        'points',
        'order_index',
    ];

    protected $casts = [
        'options' => 'array',
        'correct_answers' => 'array',
        'part' => 'integer',
        'points' => 'integer',
        'order_index' => 'integer',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
