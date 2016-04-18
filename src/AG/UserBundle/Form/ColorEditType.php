<?php

namespace AG\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ColorEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('foregroundColor', TextType::class, array(
                'label' => 'Premier plan',
                'attr' => array(
                    'class' => 'jscolor'
                )
            ))
            ->add('backgroundColor', TextType::class, array(
                'label' => 'Arrière-plan',
                'attr' => array(
                    'class' => 'jscolor'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Enregistrer'
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AG\UserBundle\Entity\User'
        ));
    }
}
