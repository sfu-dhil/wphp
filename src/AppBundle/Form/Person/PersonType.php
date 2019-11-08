<?php

namespace AppBundle\Form\Person;

use AppBundle\Entity\Geonames;
use AppBundle\Entity\Person;
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
            'label' => 'Title',
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
                'Unknown' => Person::UNKNOWN,
            ),
        ));
        $builder->add('dob', null, array(
            'label' => 'Date of Birth',
            'required' => false,
            'attr' => array(
                'help_block' => 'Person’s date of birth as YYYY-MM-DD',
            ),
        ));
        $builder->add('dod', null, array(
            'label' => 'Death Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'Person’s date of death as YYYY-MM-DD',
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
                'help_block' => 'Geotagged location of person’s birth',
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
                'help_block' => 'Geotagged location of person’s death',
            ),
        ));
        $builder->add('viafUrl', UrlType::class, array(
            'label' => 'VIAF URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a VIAF URL for this person',
            ),
        ));
        $builder->add('wikipediaUrl', UrlType::class, array(
            'label' => 'Wikipedia URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a Wikipedia URL for this person',
            ),
        ));
        $builder->add('imageUrl', UrlType::class, array(
            'label' => 'Image URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter the URL for an image for this person. Make sure the licensing on the image is permissive.',
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
                'help_block' => 'Indicates the final attempt to find person’s information',
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
