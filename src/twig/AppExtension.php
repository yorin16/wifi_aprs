<?php

namespace App\twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_question_type', [$this, 'convertQuestionType']),
        ];
    }

    public function convertQuestionType($type): string
    {
        return match ($type) {
            1 => 'Multiple choice',
            2 => 'Open',
            3 => 'Photo',
            default => '',
        };
    }
}