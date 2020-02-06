<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form definition for the format class.
 */
class FormatType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', null, array(
            'label' => 'Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('abbreviation', null, array(
            'label' => 'Abbreviation',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('description', TextareaType::class, array(
            'label' => 'Description',
            'required' => false,
            'attr' => array(
                'help_block' => 'Provide a short description of the format.',
                'class' => 'tinymce',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Format',
        ));
    }
}
