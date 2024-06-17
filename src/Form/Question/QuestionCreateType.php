<?php

namespace App\Form\Question;

use App\Controller\Admin\EditProject\QuestionController;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;

class QuestionCreateType extends AbstractType
{
    //TODO: past niet op mobile, kijken of er iets anders kan.
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $question = $options['data']['question'];
        $questionLocation = $question->getLocation();
        $builder
            ->add('text', TextType::class, [
                'label' => 'Question'
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
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
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Multiple Choice' => QuestionController::TYPE_MULTI,
                    'Open' => QuestionController::TYPE_OPEN,
                    'Make a Photo' =>  QuestionController::TYPE_PHOTO,
                ],
                'placeholder' => 'Select a question type',
            ])
            ->add('multi1', TextType::class, [
                'required' => false,
                'label' => 'Option 1 (Always the correct answer)',
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
                'label' => 'Extra info',
                'required' => false, //niet nodig, nog onduidelijk wat ik hiermee ga doen
            ])
            ->add('points', NumberType::class)
            ->add('location', ChoiceType::class, [
                'label' => 'Location',
                'choices' => $options['data']['locations'],
                'choice_label' => function ($location) {
                    $name = $location->getName();
                    $question = $location->getQuestion();
                    if ($question) {
                        return "{$name} -- In use";
                    }
                    return $name;
                },
                'choice_value' => 'id',
                'placeholder' => 'Select a location',
                'required' => false,
                'choice_attr' => function ($choice, $key, $value) use ($questionLocation) {
//                    $attrs = ['class' => 'text-white'];
                    $attrs = [];
                    if ($choice instanceof Location && ($questionLocation !== null && $choice->getId() === $questionLocation->getId())) {
                        $attrs['selected'] = 'selected';
//                        $attrs['class'] = ' text-white';
                    }else if ($choice instanceof Location && $choice->getQuestion() !== null) {
                        $attrs['disabled'] = 'disabled';
//                        $attrs['class'] = ' text-muted';
                    }

                    return $attrs;
                },
                'data' => $questionLocation ? $questionLocation->getId() : null,
            ])
            ->add('hint', TextareaType::class)
            ->add('submit', SubmitType::class);
    }
}