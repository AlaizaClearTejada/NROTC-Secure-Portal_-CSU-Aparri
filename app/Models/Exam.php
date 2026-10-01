<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'subject',
        'ms_grade_level',
        'duration_minutes',
        'start_time',
        'end_time',
        'has_part_two',
        'part_one_title',
        'part_two_title',
        'prevent_back_navigation',
        'auto_submit_on_tab_switch',
        'is_published',
        'status',
        'created_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'has_part_two' => 'boolean',
        'prevent_back_navigation' => 'boolean',
        'auto_submit_on_tab_switch' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class)->orderBy('order_index');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }
}
