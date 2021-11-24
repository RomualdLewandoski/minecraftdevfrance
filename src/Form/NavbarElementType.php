<?php

namespace App\Form;

use App\Entity\NavbarElement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom",
                'required' => true
            ])
            ->add('value', TextType::class, [
                'label' => "URL (si type = link) ou # si dropdown",
                'required' => false,
                'empty_data' => '#',
                'attr' => [
                    'placeholder' => "#"
                ]
            ])
            ->add('postion', IntegerType::class, [
                'label' => "Position",
                'required' => false,
                'empty_data' => '0',
                'attr' => [
                    'placeholder' => "0"
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => "Type",
                'placeholder' => "Choisir un type d'action",
                'required' => true,
                'choices' => [
                    "link" => "link",
                    "dropdown" => "dropdown",
                ]
            ])
            ->add('new_tab', CheckboxType::class,[
                'label' => "Ouvrir dans un nouvel onglet",
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NavbarElement::class,
        ]);
    }
}
