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
        $devicesInProject = $options['data']['devicesInProject'];

        $builder
            ->add('name')
            ->add('coordinate')
            ->add('device', EntityType::class, [
                'class' => Device::class,
                'choice_label' => function (Device $device) use ($devicesInProject) {
                    $label = $device->getName();
                    foreach ($devicesInProject as $devices) {
                        if($devices === null){
                            return $label;
                        }
                        if ($device->getGuid() === $devices->getGuid()) {
                            $label .= ' -- In use';
                            break;
                        }
                    }
                    return $label;
                },
                'placeholder' => 'No Device',
                'required' => false,
                'choice_attr' => function (Device $device, $key, $value) use ($devicesInProject) {
        // Your condition logic here
                    $found = false;
                    foreach($devicesInProject as $devices) {
                        if($devices === null){
                            break;
                        }
                        if($device->getGuid() === $devices->getGuid()){
                            $found = true;
                            break;
                        }
                    }
        $disabled = $found;

        return $disabled ? ['disabled' => 'disabled'] : [];
    },
            ])
//            ->add('coordinateHint')
            ->add('submit', SubmitType::class);
    }
}