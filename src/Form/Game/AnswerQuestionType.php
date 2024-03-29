<?php

namespace App\Form\Game;

use App\Form\Components\VisibleAnswerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question_id', HiddenType::class, [
                'data' => $options['question_id'],
            ])
            ->add('answer', VisibleAnswerType::class, [ // Use the custom form type
                'visible' => $options['question_type'], // Set visibility based on question type
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Answer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['question_id', 'question_type']);
    }
}