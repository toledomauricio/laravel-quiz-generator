<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'started_at',
        'completed_at',
        'score',
        'percentage',
        'passed',
        'time_spent',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'score' => 'integer',
            'percentage' => 'decimal:2',
            'passed' => 'boolean',
            'time_spent' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attemptAnswers(): HasMany
    {
        return $this->hasMany(AttemptAnswer::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isInProgress(): bool
    {
        return $this->completed_at === null;
    }

    public function calculateScore(): void
    {
        $totalPoints = $this->quiz->totalPoints();
        $earnedPoints = $this->attemptAnswers()->sum('points_earned');
        
        $this->score = $earnedPoints;
        $this->percentage = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $this->passed = $this->percentage >= $this->quiz->passing_score;
        $this->save();
    }

    public function complete(): void
    {
        $this->completed_at = now();
        $this->time_spent = now()->diffInSeconds($this->started_at);
        $this->calculateScore();
    }

    public function scopeCompleted($query)
    {
        return $query->whereNotNull('completed_at');
    }

    public function scopeInProgress($query)
    {
        return $query->whereNull('completed_at');
    }
}
