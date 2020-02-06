<?php

namespace App\Form\Person;

use App\Entity\Geonames;
use App\Entity\Person;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Form definition for the person class.
 */
class PersonType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('lastName', null, array(
            'label' => 'Last Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.lastName',
            ),
        ));
        $builder->add('firstName', null, array(
            'label' => 'First Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.firstName',
            ),
        ));
        $builder->add('title', null, array(
            'label' => 'Title',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.title',
            ),
        ));
        $builder->add('gender', ChoiceType::class, array(
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Unknown' => Person::UNKNOWN,
            ),
            'attr' => array(
                'help_block' => 'person.form.gender',
            ),
        ));
        $builder->add('dob', null, array(
            'label' => 'Date of Birth',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.dob',
            ),
        ));
        $builder->add('dod', null, array(
            'label' => 'Death Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.dod',
            ),
        ));
        $builder->add('cityOfBirth', Select2EntityType::class, array(
            'label' => 'Place of Birth',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'person.form.cityOfBirth',
            ),
        ));
        $builder->add('cityOfDeath', Select2EntityType::class, array(
            'label' => 'Place of Death',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'person.form.cityOfDeath',
            ),
        ));
        $builder->add('viafUrl', UrlType::class, array(
            'label' => 'VIAF URI',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.viafUrl',
            ),
        ));
        $builder->add('wikipediaUrl', UrlType::class, array(
            'label' => 'Wikipedia URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.wikipediaUrl',
            ),
        ));
        $builder->add('imageUrl', UrlType::class, array(
            'label' => 'Image URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.form.imageUrl',
            ),
        ));
        $builder->add('finalcheck', ChoiceType::class, array(
            'label' => 'Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => array(
                'Yes' => true,
                'No' => false,
            ),
            'required' => true,
            'placeholder' => false,
            'attr' => array(
                'help_block' => 'person.form.finalCheck',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Person::class,
        ));
    }
}
