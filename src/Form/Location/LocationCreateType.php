<?php

namespace App\Form\Location;

use App\Entity\Device;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LocationCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Coordinate')
            ->add('device', EntityType::class, [
                'class' => Device::class,
                'choice_label' => 'name',
                'placeholder' => 'No Device',
                'required' => false
            ])
            ->add('submit', SubmitType::class);
    }
}