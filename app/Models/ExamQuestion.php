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

    /**
     * Drop blank choices and re-point the correct-answer key ("option_N") at the
     * choice's new position. Returns null when the marked choice was blank.
     *
     * @param  array<int|string, string|null>  $options
     * @return array{options: array<int, string>, correct_answer: string}|null
     */
    public static function compactChoices(array $options, string $correctKey): ?array
    {
        $choices = [];
        $correctAnswer = null;

        foreach ($options as $index => $text) {
            if (blank($text)) {
                continue;
            }

            if ($correctKey === 'option_'.$index) {
                $correctAnswer = 'option_'.count($choices);
            }

            $choices[] = $text;
        }

        if ($correctAnswer === null || count($choices) < 2) {
            return null;
        }

        return ['options' => $choices, 'correct_answer' => $correctAnswer];
    }

    /**
     * Split an enumeration answer typed as one block ("a, b" or one per line) into items.
     *
     * @param  array<int, string>|string  $answer
     * @return array<int, string>
     */
    public static function enumerationItems(array|string $answer): array
    {
        $parts = preg_split('/[,\r\n]+/', is_array($answer) ? implode("\n", $answer) : $answer);

        return array_values(array_filter(array_map(fn ($item) => strtolower(trim($item)), $parts), fn ($item) => $item !== ''));
    }
}
