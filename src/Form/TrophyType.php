<?php

namespace App\Form;

use App\Entity\Trophy;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrophyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('bgColor', ColorType::class, [
                'label' => "Couleur du fond"
            ])
            ->add('description')
            ->add('isSuper', CheckboxType::class, [
                'required' => false,
                'label' => "Est ce un super trophée ? "
            ])
            ->add('action', ChoiceType::class, [
                'placeholder' => "Choisir une action",
                'choices' => [
                    "Poster sujet (value = nombre de sujet)" => 0,
                    "Poster réponse (value = nombre de réponse)" => 1,
                    "Recevoir like (value = nombre de like)" => 2,
                    "Donner like (value = nombre de like)" => 3,
                    "Ancienneté (value = nombre de jours 365 = 1 an ) " => 4,
                    "Recevoir super like (value = nombre de super like) " => 5
                    ]
            ])
            ->add('value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trophy::class,
        ]);
    }
}
