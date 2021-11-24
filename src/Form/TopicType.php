<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Topic;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TopicType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('message', CKEditorType::class)
            ->add('tags', EntityType::class, [
                'mapped' => false,
                'class' => Tag::class,
                'choice_label' => 'nom',
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => "chosen-select"
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Topic::class,
        ]);
    }
}
