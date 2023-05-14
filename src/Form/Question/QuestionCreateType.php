<?php

namespace App\Form\Question;

use App\Entity\Location;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Multiple Choice' => '1',
                    'Open' => '2',
                ],
                'placeholder' => 'Select a question type',
            ])
            ->add('multi1', TextType::class, [
                'required' => false,
                'label' => 'Option 1',
            ])
            ->add('multi2', TextType::class, [
                'required' => false,
                'label' => 'Option 2',
            ])
            ->add('multi3', TextType::class, [
                'required' => false,
                'label' => 'Option 3',
            ])
            ->add('multi4', TextType::class, [
                'required' => false,
                'label' => 'Option 4',
            ])
            ->add('open', TextType::class, [
                'required' => false, //niet nodig, nog onduidelijk wat ik hiermee ga doen
            ])
            ->add('points', NumberType::class)
            ->add('Location', EntityType::class,[
                'class' => Location::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('l')
                        ->where('l.Project = :projectId')
                        ->setParameter('projectId', $options['data']['projectId']);
                },
                'choice_label' => 'name',
                'placeholder' => 'No Location',
                'required' => false
            ])
            ->add('submit', SubmitType::class);
    }
}