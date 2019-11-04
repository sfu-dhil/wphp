<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form definition for the source class.
 */
class SourceType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', null, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('onlineSource', UrlType::class, array(
            'label' => 'Online Source',
            'required' => false,
            'attr' => array(
                'help_block' => 'Optional. Enter the URL for the source.',
            ),
        ));
        $builder->add('description', TextareaType::class, array(
            'label' => 'Description',
            'required' => false,
            'attr' => array(
                'help_block' => 'Provide a short description of the source.',
                'class' => 'tinymce',
            ),
        ));
        $builder->add('citation', TextareaType::class, array(
            'label' => 'Citation',
            'required' => false,
            'attr' => array(
                'help_block' => 'Provide citation for the source.',
                'class' => 'tinymce',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Source',
        ));
    }
}
