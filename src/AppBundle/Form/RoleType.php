<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType {

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
        $builder->add('description', TextAreaType::class, array(
            'label' => 'Description',
            'required' => false,
            'attr' => array(
                'help_block' => 'Provide a short description of the source.',
                'class' => 'tinymce',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Role'
        ));
    }

}
