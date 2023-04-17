<?php

namespace App\Form\Question;

use App\Entity\Location;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text')
            ->add('type')
            ->add('points')
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