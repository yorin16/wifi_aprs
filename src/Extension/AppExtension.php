<?php

namespace App\Extension;

use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(private Security $security)
    {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('convert_question_type', [$this, 'convertQuestionType']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('user_projects', [$this, 'getUserProjects']),
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

    public function getUserProjects()
    {
        $user = $this->security->getUser();

        return $user?->getProject();
    }
}