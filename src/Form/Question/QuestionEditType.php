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
                    'Multiple Choice' => QuestionController::TYPE_MULTI,
                    'Open' => QuestionController::TYPE_OPEN,
                ],
                'placeholder' => 'Select a question type',
                'data' => $question->getType(),
            ])
            ->add('multi1', TextType::class, [
                'required' => false,
                'label' => 'Option 1',
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
                'required' => false, //niet nodig, nog onduidelijk wat ik hiermee ga doen
                'data' => $question->getOpen()
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