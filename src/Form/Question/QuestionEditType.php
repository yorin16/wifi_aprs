<?php

namespace App\Form\Question;

use App\Controller\Admin\EditProject\QuestionController;
use App\Entity\Location;
use App\Entity\Question;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\File\File as ImageFile;

class QuestionEditType extends AbstractType
{
    public function __construct(private ParameterBagInterface $parameterBag){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Question $question */
        $question = $options['question'];
        $questionLocation = $question->getLocation();

        if ($question->getImage()) {
            $imagePath = $this->parameterBag->get('question_images') . '/' . $question->getImage();
            $file = new ImageFile($imagePath);
        } else {
            $file = null;
        }

        $builder
            ->add('text', TextType::class, [
                'label' => 'Question',
                'data' => $question->getText(),
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
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Multiple Choice' => QuestionController::TYPE_MULTI,
                    'Open' => QuestionController::TYPE_OPEN,
                    'Make a Photo' =>  QuestionController::TYPE_PHOTO,
                ],
                'placeholder' => 'Select a question type',
                'data' => $question->getType(),
            ])
            ->add('multi1', TextType::class, [
                'required' => false,
                'label' => 'Option 1 (Correct Answer)',
                'data' => $question->getMulti1(),
            ])
            ->add('multi2', TextType::class, [
                'required' => false,
                'label' => 'Option 2',
                'data' => $question->getMulti2(),
            ])
            ->add('multi3', TextType::class, [
                'required' => false,
                'label' => 'Option 3',
                'data' => $question->getMulti3(),
            ])
            ->add('multi4', TextType::class, [
                'required' => false,
                'label' => 'Option 4',
                'data' => $question->getMulti4(),
            ])
            ->add('open', TextType::class, [
                'label' => 'Extra info',
                'required' => false, //niet nodig, nog onduidelijk wat ik hiermee ga doen
                'data' => $question->getOpen()
            ])
            ->add('points', IntegerType::class, [
                'data' => $question->getPoints(),
                'required' => false
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'Location',
                'choices' => $options['locations'],
                'choice_label' => function ($location) use ($question) {
                    $name = $location->getName();
                    $QeustionLocation = $location->getQuestion();

                    if ($QeustionLocation) {
                        return "{$name} -- In use";
                    }

                    if($question === null || $QeustionLocation === null || $question->getLocation() === null) {
                        return $name;
                    }

                    if ($QeustionLocation->getLocation()->getId() === $question->getLocation()->getId()) {
                        return "{$name} -- Current Location";
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
            ->add('hint', TextareaType::class, ['required' => false])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'question' => false,
            'projectId' => false,
            'locations' => false,
        ]);

    }
}