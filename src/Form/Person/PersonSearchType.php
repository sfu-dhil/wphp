<?php

namespace App\Form\Person;

use App\Form\Firm\FirmFilterType;
use App\Form\Title\TitleFilterType;
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
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');

        $builder->add('name', TextType::class, array(
            'label' => 'Search Persons by Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.name',
            ),
        ));

        $builder->add('order', ChoiceType::class, array(
            'label' => 'Results Sorted By',
            'choices' => array(
                'Last Name (A to Z)' => 'lastname_asc',
                'Last Name (Z to A)' => 'lastname_desc',
                'First Name (A to Z)' => 'firstname_asc',
                'First Name (Z to A)' => 'firstname_desc',
                'Gender (Unknown, Female, Male)' => 'gender_asc',
                'Gender (Male, Female, Unknown)' => 'gender_desc',
                'Birth Date (Oldest First)' => 'birth_asc',
                'Birth Date (Youngest First)' => 'birth_desc',
                'Death Date (Most Recent Last)' => 'death_asc',
                'Death Date (Most Recent First)' => 'death_desc',
            ),
            'attr' => array(
                'help_block' => 'Choose a sort method for the results',
            ),
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('id', TextType::class, array(
            'label' => 'Person ID',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.id',
            ),
        ));

        $builder->add('gender', ChoiceType::class, array(
            'label' => 'Gender',
            'choices' => array(
                'Female' => 'F',
                'Male' => 'M',
                'Unknown' => 'U',
            ),
            'attr' => array(
                'help_block' => 'person.search.gender',
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('dob', TextType::class, array(
            'label' => 'Date of Birth',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.dob',
            ),
        ));

        $builder->add('dod', TextType::class, array(
            'label' => 'Date of Death',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.dod',
            ),
        ));

        $builder->add('birthplace', TextType::class, array(
            'label' => 'Place of Birth',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.cityOfBirth',
            ),
        ));

        $builder->add('deathplace', TextType::class, array(
            'label' => 'Place of Death',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.cityOfDeath',
            ),
        ));

        $builder->add('viafUrl', TextType::class, array(
            'label' => 'VIAF URI',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.viafUrl',
            ),
        ));
        $builder->add('wikipediaUrl', TextType::class, array(
            'label' => 'Wikipedia URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.wikipediaUrl',
            ),
        ));
        $builder->add('imageUrl', TextType::class, array(
            'label' => 'Image URL',
            'required' => false,
            'attr' => array(
                'help_block' => 'person.search.imageUrl',
            ),
        ));

        $builder->add('title_filter', TitleFilterType::class, array(
            'label' => 'Filter by Title',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
            ),
        ));

        $builder->add('firm_filter', FirmFilterType::class, array(
            'label' => 'Filter by Firm',
            'required' => false,
            'attr' => array(
                'class' => 'embedded-form',
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
