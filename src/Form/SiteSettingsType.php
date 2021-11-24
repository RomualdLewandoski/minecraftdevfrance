<?php

namespace App\Form;

use App\Entity\SiteSettings;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteSettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cgu', CKEditorType::class, [
                'label' => "PAGE CGU",
                'required' => false,
                'empty_data' => "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vitae felis luctus, accumsan ipsum quis, vestibulum diam. Integer at vulputate magna, quis ullamcorper eros. Morbi at dolor ac neque egestas pulvinar. Cras nec sapien quis enim ultricies vulputate. Maecenas ullamcorper justo id eros pulvinar dapibus. Nullam ultrices, magna vitae viverra tristique, magna nunc posuere turpis, eget molestie massa odio vitae sem. Sed et faucibus diam. Nulla a arcu justo. Vestibulum congue augue sit amet molestie placerat. Nunc aliquet pulvinar lacus, id viverra libero pulvinar at. Sed at lectus mi. Pellentesque accumsan ligula sed aliquet consectetur.</p>"
            ])
            ->add('rgpd', CKEditorType::class, [
                'label' => "RGPD",
                'required' => false,
                'empty_data' => "<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In vitae felis luctus, accumsan ipsum quis, vestibulum diam. Integer at vulputate magna, quis ullamcorper eros. Morbi at dolor ac neque egestas pulvinar. Cras nec sapien quis enim ultricies vulputate. Maecenas ullamcorper justo id eros pulvinar dapibus. Nullam ultrices, magna vitae viverra tristique, magna nunc posuere turpis, eget molestie massa odio vitae sem. Sed et faucibus diam. Nulla a arcu justo. Vestibulum congue augue sit amet molestie placerat. Nunc aliquet pulvinar lacus, id viverra libero pulvinar at. Sed at lectus mi. Pellentesque accumsan ligula sed aliquet consectetur.</p>"
            ])
            ->add('youtube', TextType::class, [
                'label' => "URL de la dernière vidéo youtube",
                'required' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SiteSettings::class,
        ]);
    }
}
