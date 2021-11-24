<?php

namespace App\Form;

use App\Entity\CatForum;
use App\Entity\Forum;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ForumType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('position')
            ->add('isLocked')
            ->add('isActive')
            ->add('catForum', EntityType::class, array(
                'class' => CatForum::class,
                'placeholder' => "Choisir une catégorie",
                'choice_label' => 'name',
                'multiple' => false,
                'required' => false,
            ))
            ->add('parent', EntityType::class, array(
                'class' => Forum::class,
                'placeholder' => "Choisir un forum",
                'group_by' => 'CatName',
                'required' => false,
                'empty_data' => null,
                'choice_label' => function(Forum $forum){
                    /*if ($forum->getParent() != null){
                        $parent = $forum->getParent();
                        return "(".$parent->getNom().") - ".$forum->getNom();
                    }*/
                    return $forum->getDisplayName();
                },
                'multiple' => false,
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Forum::class,
        ]);
    }
}
