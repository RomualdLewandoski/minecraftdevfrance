<?php

namespace App\Form;

use App\Entity\NavbarMenu;
use App\Entity\NavbarSubMenu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavbarSubMenuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true
            ])
            ->add('position', IntegerType::class, [
                'required' => false,
                'empty_data' => "0",
                'attr' => [
                    'placeholder' => "0"
                ]
            ])
            ->add('value', TextType::class, [
                'required' => false,
                'empty_data' => "#",
                'attr' => [
                    'placeholder' => "#"
                ]
            ])
            ->add('parent', EntityType::class, [
                'placeholder' => "Choisir un onglet",
                "class" => NavbarMenu::class,
                "choice_label" => "nom",
                'multiple' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NavbarSubMenu::class,
        ]);
    }
}
