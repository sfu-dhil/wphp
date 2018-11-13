<?php

namespace AppBundle\Form;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormatType extends AbstractType {

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
        $builder->add('abbrevOne', null, array(
            'label' => 'Abbrev One',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('abbrevTwo', null, array(
            'label' => 'Abbrev Two',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('abbrevThree', null, array(
            'label' => 'Abbrev Three',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('abbrevFour', null, array(
            'label' => 'Abbrev Four',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('description', CKEditorType::class, array(
            'label' => 'Description',
            'required' => false,
            'attr' => array(
                'help_block' => 'Provide a short description of the format.',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Format'
        ));
    }

}
