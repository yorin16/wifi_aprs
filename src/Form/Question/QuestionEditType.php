<?php

namespace App\Form\Question;

use App\Controller\Admin\EditProject\QuestionController;
use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class QuestionEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $question = $options['data']['question'];
        $questionLocation = $question->getLocation();

        $builder
            ->add('text', TextType::class, [
                'data' => $question->getText(),
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Multiple choice' => QuestionController::TYPE_MULTI,
                    'Open' => QuestionController::TYPE_OPEN,
                    'Photo' => QuestionController::TYPE_PHOTO,
                ],
                'data' => $question->getType(),
            ])
            ->add('points', IntegerType::class, [
                'data' => $question->getPoints(),
                'required' => false
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'Locations',
                'choices' => $options['data']['locations'],
                'choice_label' => function ($location) {
                    $name = $location->getName();
                    $question = $location->getQuestion();
                    if ($question) {
                        return "{$name} (Already used)";
                    }
                    return $name;
                },
                'choice_value' => 'id',
                'placeholder' => 'Select a location',
                'required' => false,
                'choice_attr' => function ($choice, $key, $value) use ($questionLocation) {
                    $attrs = ['class' => 'text-white'];
                    if ($choice instanceof Location && ($questionLocation !== null && $choice->getId() === $questionLocation->getId())) {
                        $attrs['selected'] = 'selected';
                        $attrs['class'] = ' text-white';
                    }else if ($choice instanceof Location && $choice->getQuestion() !== null) {
                        $attrs['disabled'] = 'disabled';
                        $attrs['class'] = ' text-muted';
                    }

                    return $attrs;
                },
                'data' => $questionLocation ? $questionLocation->getId() : null,
            ])
            ->add('submit', SubmitType::class);
    }
}