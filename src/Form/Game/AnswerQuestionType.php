<?php

namespace App\Form\Game;

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
            ->add('selected_answer', HiddenType::class)
            ->add('submit', SubmitType::class, [
                'label' => 'Answer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('question_id');
    }
}