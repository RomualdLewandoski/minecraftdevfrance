<?php

namespace App\Form;

use App\Entity\NavbarMenu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true
            ])
            ->add('value', TextType::class, [
                'required' => false,
                'empty_data' => "#",
                'attr' => [
                    'placeholder' => "#"
                ]
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
                'label' => "Position",
                'empty_data' => "0",
                'attr' => [
                    'placeholder' => "0"
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => "Type",
                'placeholder' => "Choisir un type d'action",
                'required' => true,
                'choices' => [
                    "link" => "0",
                    "dropdown" => "1",
                ]
            ])
            ->add('isNewtab', CheckboxType::class, [
                'label' => "Ouverture dans un nouvel onglet",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NavbarMenu::class,
        ]);
    }
}
