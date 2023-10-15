<?php

namespace App\Form\Location;

use App\Entity\Device;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $devicesInProject = $options['devicesInProject'];
        $currentDevice = $options['currentDevice'];

        $builder
            ->add('name')
            ->add('coordinate')
            ->add('device', EntityType::class, [
                'class' => Device::class,
                'choice_label' => function (Device $device) use ($devicesInProject, $currentDevice) {
                    $label = $device->getName();
                    if(count($device->getLocations()) === 0){
                        return $label;
                    }
                    if ($currentDevice && $device->getGuid() === $currentDevice->getGuid()) {
                        return $label .= ' -- Current Device';
                    }
                    return $label .= ' -- In use';
                },
                'placeholder' => 'No Device',
                'required' => false,
                'choice_attr' => function (Device $device, $key, $value) use ($devicesInProject, $currentDevice) {
                    $found = false;
                    foreach ($devicesInProject as $devices) {
                        if($devices === null){
                            break;
                        }
                        if ($device->getGuid() === $devices->getGuid()) {
                            $found = true;
                            break;
                        }
                    }
                    $disabled = $found && $device !== $currentDevice;

                    return $disabled ? ['disabled' => 'disabled'] : [];
                },
            ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'devicesInProject' => false,
            'currentDevice' => false,
        ]);

    }
}
