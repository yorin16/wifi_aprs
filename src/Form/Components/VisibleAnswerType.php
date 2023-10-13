<?php

namespace App\Form\Components;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VisibleAnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['visible'] == 1) {
            $builder->add('selected_answer', HiddenType::class);
        } elseif($options['visible'] == 2) {
            $builder->add('open', TextareaType::class,[
                "label" => false
            ]);
        } elseif($options['visible'] == 3) {
            $builder->add('image', FileType::class,[
                "label" => false,
                'constraints' => [
                    new File([
                        'maxSize' => '16M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'extensions' => ['jpg', 'jpeg', 'png'],
                        'extensionsMessage' => 'Allowed file extensions are: .jpg, .jpeg, .png',
                        'mimeTypesMessage' => 'Please upload a valid JPG or PNG image',
                        'maxSizeMessage' => 'The file is too large ({{ size }} {{ suffix }}). Max allowed size is {{ limit }} {{ suffix }}.',
                    ])
                ]
            ]);

        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'visible' => 1,
        ]);
    }
}