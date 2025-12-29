<?php

namespace App\Enums;

enum QuestionType: string
{
    case MULTIPLE_CHOICE = 'multiple_choice';
    case TRUE_FALSE = 'true_false';
    case SHORT_ANSWER = 'short_answer';

    public function label(): string
    {
        return match($this) {
            self::MULTIPLE_CHOICE => 'Multiple Choice',
            self::TRUE_FALSE => 'True/False',
            self::SHORT_ANSWER => 'Short Answer',
        };
    }

    public function requiresOptions(): bool
    {
        return match($this) {
            self::MULTIPLE_CHOICE => true,
            self::TRUE_FALSE => true,
            self::SHORT_ANSWER => false,
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
