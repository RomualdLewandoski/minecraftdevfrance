<?php

namespace App\Form;

use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('newPass', PasswordType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Nouveau mot de passe"
            ])
            ->add('useDoomFont', CheckboxType::class, [
                "label" => "Autoriser la police easteregg",
                'required' => false
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => "Mot de passe actuel *",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your password',
                    ]),
                ],
            ])
            ->add('signature', CKEditorType::class, [
                'required' => false
            ])
            ->add('isMinecraftAvatar', CheckboxType::class, [
                'label' => "Utiliser votre avatar Minecraft comme photo de profil au lieu de gravatar ?",
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
