<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::TEACHER => 'Teacher',
            self::STUDENT => 'Student',
        };
    }

    public function permissions(): array
    {
        return match($this) {
            self::ADMIN => ['*'],
            self::TEACHER => [
                'quiz.create',
                'quiz.update',
                'quiz.delete',
                'quiz.view',
                'question.create',
                'question.update',
                'question.delete',
            ],
            self::STUDENT => [
                'quiz.view',
                'quiz.attempt',
            ],
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
