<?php

namespace AppBundle\Form\Person;

use AppBundle\Form\Firm\FirmFilterType;
use AppBundle\Form\Title\TitleFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Search form for person entities.
 */
class PersonSearchType extends AbstractType {

    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setMethod('get');

        $builder->add('name', TextType::class, array(
            'label' => 'Search People by Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter all or part of a personal name'
            ),
        ));

        $builder->add('order', ChoiceType::class, array(
            'label' => 'Results Sorted By',
            'choices' => array(
                'Last Name (A to Z)' => 'lastname_asc',
                'Last Name (Z to A)' => 'lastname_desc',
                'First name (A to Z)' => 'firstname_asc',
                'First name (Z to A)' => 'firstname_desc',
                'Gender (Unknown, Female, Male)' => 'gender_asc',
                'Gender (Male, Female, Unknown)' => 'gender_desc',
                'Birth Date (Oldest First)' => 'birth_asc',
                'Birth Date (Youngest First)' => 'birth_desc',
                'Death Date (Most Recent Last)' => 'death_asc',
                'Death Date (Most Recent First)' => 'death_desc',
            ),
            'attr' => array(
                'help_block' => 'Choose a sort method for the results'
            ),
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('id', TextType::class, array(
            'label' => 'Firm ID',
            'required' => false,
            'attr' => array(
                'help_block' => 'Find this exact person ID.',
            )
        ));

        $builder->add('gender', ChoiceType::class, array(
            'label' => 'Gender',
            'choices' => array(
                'Female' => 'F',
                'Male' => 'M',
                '(unknown)' => 'U',
            ),
            'attr' => array(
                'help_block' => 'Leave this field blank to include all genders'
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('dob', TextType::class, array(
            'label' => 'Birth Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a year (eg <kbd>1795</kbd>) or range of years (<kbd>1790-1800</kbd>) or a partial range of years (<kbd>*-1800</kbd>)',
            ),
        ));

        $builder->add('dod', TextType::class, array(
            'label' => 'Death Year',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a year (eg <kbd>1795</kbd>) or range of years (<kbd>1790-1800</kbd>) or a partial range of years (<kbd>*-1800</kbd>)',
            ),
        ));

        $builder->add('birthplace', TextType::class, array(
            'label' => 'Birth Place',
            'required' => false,

        ));

        $builder->add('deathplace', TextType::class, array(
            'label' => 'Death Place',
            'required' => false,

        ));

        $builder->add('viafUrl', TextType::class, array(
            'label' => 'VIAF Url',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a VIAF URI to check if we have a corresponding record. Enter <kbd>blank</kbd> to find records which do not have VIAF URIs.',
            ),
        ));
        $builder->add('wikipediaUrl', TextType::class, array(
            'label' => 'Wikipedia URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter a Wikipedia URL to check if we have a corresponding record. Enter <kbd>blank</kbd> to find records which do not have Wikipedia URLs.',
            ),
        ));
        $builder->add('imageUrl', TextType::class, array(
            'label' => 'Death Place',
            'required' => false,
            'attr' => array(
                'help_block' => 'Enter an image URL to check if we have a corresponding record. Enter <kbd>blank</kbd> to find records which do not have image URLs.',
            ),
        ));

        $builder->add('title_filter', TitleFilterType::class, array(
            'label' => 'Filter by Title',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form'
            ),
        ));

        $builder->add('firm_filter', FirmFilterType::class, array(
            'label' => 'Filter by Firm',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form'
            ),
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        parent::configureOptions($resolver);
        $resolver->setRequired(array('entity_manager'));
    }

}
