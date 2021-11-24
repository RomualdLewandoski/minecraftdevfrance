<?php

namespace App\Form;

use App\Entity\UserRank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => "Nom du rôle"
            ])
            ->add('color', ColorType::class, [
                'label' => "Couleur du role"
            ])
            ->add('Priority', IntegerType::class, [
                'label' => "Ordre de priorité"
            ])
            ->add('canManageForum', CheckboxType::class, [
                'label'=> "Gestion Forum",
                "required" => false
            ])
            ->add('hasRepportPanel', CheckboxType::class,[
                'label' => "Gestion report",
                "required" => false
            ])
            ->add('canManageWall', CheckboxType::class,[
                'label' => "Gestion profils",
                "required" => false
            ])
            ->add('isDisplayStaff', CheckboxType::class, [
                'label' => "Affichage sur la page staff",
                "required" => false
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRank::class,
        ]);
    }
}
