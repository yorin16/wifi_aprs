<?php

namespace App\Form\Question;

use App\Entity\Location;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text')
            ->add('type')
            ->add('points')
            ->add('Location', EntityType::class,[
                'class' => Location::class,
                'choice_label' => 'coordinate',
                'required' => true
            ])
            ->add('submit', SubmitType::class);
    }
}