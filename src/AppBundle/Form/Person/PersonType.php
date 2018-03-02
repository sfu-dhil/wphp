<?php

namespace AppBundle\Form\Person;

use AppBundle\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('lastName', null, array(
            'label' => 'Last Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('firstName', null, array(
            'label' => 'First Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('title', null, array(
            'label' => 'Title Name',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('gender', null, array(
            'label' => 'Gender',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('dob', null, array(
            'label' => 'Dob',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('dod', null, array(
            'label' => 'Dod',
            'required' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('checked', ChoiceType::class, array(
            'label' => 'Checked',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
        $builder->add('finalcheck', ChoiceType::class, array(
            'label' => 'Person Finalcheck',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => '',
            ),
        ));
//        $builder->add('cityOfBirth');
//        $builder->add('cityOfDeath');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Person::class
        ));
    }

}
