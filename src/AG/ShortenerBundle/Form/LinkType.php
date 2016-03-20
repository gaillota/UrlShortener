<?php

namespace AG\ShortenerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', TextareaType::class, array(
                'label' => 'URL',
                'attr' => array(
                    'placeholder' => 'Collez ici votre longue URL...'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Raccourcir',
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AG\ShortenerBundle\Entity\Link'
        ));
    }
}
