<?php

namespace App\Form\Answer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AnswerScoreEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
           ->add('points', IntegerType::class, [
               'required' => true
           ])
           ->add('submit', SubmitType::class);
    }
}