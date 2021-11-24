<?php

namespace App\Form;

use App\Entity\UserInfo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isPublic', CheckboxType::class, [
                'label' => "Le profil est il visible aux utilisateurs non connectés ?  ",
                'required' => false,
            ])
            ->add('gender', ChoiceType::class, [
                'label' => "Genre",
                'required' => false,
                'choices' => [
                    "Homme" => "Homme",
                    "Femme" => "Femme",
                    "Autre" => "Autre"
                ]
            ])
            ->add('isGender', CheckboxType::class, [
                'label' => "Afficher le genre",
                'required' => false,
            ])
            ->add('birthDate', DateType::class,[
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('isBirthDate', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher l'anniversaire"
            ])
            ->add('homePage', TextType::class,[
                'required' => false,
                'label' => "Votre site (tout abus serra punis)"
            ])
            ->add('isHomePage', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre site"
            ])
            ->add('country', TextType::class, [
                'required' => false,
                'label' => "Votre pays"
            ])
            ->add('isCountry', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre pays"
            ])
            ->add('job', TextType::class,[
                'required' => false,
                'label' => "Profession"
            ])
            ->add('isJob', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre profession"
            ])
            ->add('steam', TextType::class,[
                'required' => false,
            ])
            ->add('isSteam', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre Steam"
            ])
            ->add('minecraft', TextType::class,[
                'required' => false,
            ])
            ->add('isMinecraft', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre Minecraft"
            ])
            ->add('twitch', TextType::class,[
                'required' => false,
            ])
            ->add('isTwitch',CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre Twitch"
            ])
            ->add('discord', TextType::class,[
                'required' => false,
            ])
            ->add('isDiscord', CheckboxType::class,[
                'required' => false,
                'label' => "Afficher votre Discord"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserInfo::class,
        ]);
    }
}
