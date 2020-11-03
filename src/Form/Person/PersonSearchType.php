<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Person;

use App\Entity\Person;
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
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');

        $builder->add('name', TextType::class, [
            'label' => 'Search Persons by Name',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.name',
            ],
        ]);

        $builder->add('order', ChoiceType::class, [
            'label' => 'Results Sorted By',
            'choices' => [
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
            ],
            'attr' => [
                'help_block' => 'Choose a sort method for the results',
            ],
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Person ID',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.id',
            ],
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Transgender' => Person::TRANS,
                'Unknown' => Person::UNKNOWN,
            ],
            'attr' => [
                'help_block' => 'person.search.gender',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'empty_data' => null,
            'data' => null,
        ]);

        $builder->add('dob', TextType::class, [
            'label' => 'Date of Birth',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.dob',
            ],
        ]);

        $builder->add('dod', TextType::class, [
            'label' => 'Date of Death',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.dod',
            ],
        ]);

        $builder->add('birthplace', TextType::class, [
            'label' => 'Place of Birth',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.cityOfBirth',
            ],
        ]);

        $builder->add('deathplace', TextType::class, [
            'label' => 'Place of Death',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.cityOfDeath',
            ],
        ]);

        $builder->add('viafUrl', TextType::class, [
            'label' => 'VIAF URI',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.viafUrl',
            ],
        ]);
        $builder->add('wikipediaUrl', TextType::class, [
            'label' => 'Wikipedia URL',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.wikipediaUrl',
            ],
        ]);
        $builder->add('imageUrl', TextType::class, [
            'label' => 'Image URL',
            'required' => false,
            'attr' => [
                'help_block' => 'person.search.imageUrl',
            ],
        ]);

        $builder->add('title_filter', TitleFilterType::class, [
            'label' => 'Filter by Title',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('firm_filter', FirmFilterType::class, [
            'label' => 'Filter by Firm',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) : void {
        parent::configureOptions($resolver);
        $resolver->setRequired(['entity_manager']);
    }
}
