<?php

namespace AppBundle\Form\Person;

use AppBundle\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

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
        $builder->add('gender', ChoiceType::class, array(
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => null,
            ),            
        ));
        $builder->add('dob', null, array(
            'label' => 'Dob',
            'required' => false,
            'attr' => array(
                'help_block' => 'Person’s date of birth as YYYY-MM-DD',
            ),
        ));
        $builder->add('cityOfBirth', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en', 
            'attr' => array(
                'help_block' => 'Geotagged location of person’s birth'
            ),
        ));
        $builder->add('dod', null, array(
            'label' => 'Dod',
            'required' => false,
            'attr' => array(
                'help_block' => 'Person’s date of death as YYYY-MM-DD',
            ),
        ));
        $builder->add('cityOfDeath', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'Geotagged location of person’s death'
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
                'help_block' => 'Indicates the attempt to find person’s information',
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
                'help_block' => 'Indicates the final attempt to find person’s information',
            ),
        ));
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
