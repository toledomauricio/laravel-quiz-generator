<?php

namespace App\Models;

use App\Enums\DifficultyLevel;
use App\Enums\QuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'explanation',
        'difficulty',
        'points',
        'order',
        'is_required',
    ];

    protected function casts(): array
    {
        return [
            'type' => QuestionType::class,
            'difficulty' => DifficultyLevel::class,
            'points' => 'integer',
            'order' => 'integer',
            'is_required' => 'boolean',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)->orderBy('order');
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function correctAnswers(): HasMany
    {
        return $this->answers()->where('is_correct', true);
    }

    public function isMultipleChoice(): bool
    {
        return $this->type === QuestionType::MULTIPLE_CHOICE;
    }

    public function isTrueFalse(): bool
    {
        return $this->type === QuestionType::TRUE_FALSE;
    }

    public function isShortAnswer(): bool
    {
        return $this->type === QuestionType::SHORT_ANSWER;
    }
}
