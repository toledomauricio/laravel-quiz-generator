<?php

namespace App\Models;

use App\Enums\QuizStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'time_limit',
        'passing_score',
        'shuffle_questions',
        'show_results',
    ];

    protected function casts(): array
    {
        return [
            'status' => QuizStatus::class,
            'time_limit' => 'integer',
            'passing_score' => 'integer',
            'shuffle_questions' => 'boolean',
            'show_results' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function isPublished(): bool
    {
        return $this->status === QuizStatus::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->status === QuizStatus::DRAFT;
    }

    public function totalPoints(): int
    {
        return $this->questions()->sum('points');
    }

    public function scopePublished($query)
    {
        return $query->where('status', QuizStatus::PUBLISHED);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
