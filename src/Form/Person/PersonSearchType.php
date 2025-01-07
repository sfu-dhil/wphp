<?php

declare(strict_types=1);

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
        $user = $options['user'];
        $builder->setMethod('get');

        $builder->add('name', TextType::class, [
            'label' => 'Search Persons by Name',
            'required' => false,
            'help' => 'person.search.name',
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
            'help' => 'Choose a sort method for the results',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
            'empty_data' => null,
            'data' => null,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Person ID',
            'required' => false,
            'help' => 'person.search.id',
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Transgender' => Person::TRANS,
                'Unknown' => Person::UNKNOWN,
            ],
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'help' => 'person.search.gender',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('dob', TextType::class, [
            'label' => 'Date of Birth',
            'required' => false,
            'help' => 'person.search.dob',
        ]);

        $builder->add('dod', TextType::class, [
            'label' => 'Date of Death',
            'required' => false,
            'help' => 'person.search.dod',
        ]);

        $builder->add('birthplace', TextType::class, [
            'label' => 'Place of Birth',
            'required' => false,
            'help' => 'person.search.cityOfBirth',
        ]);

        $builder->add('deathplace', TextType::class, [
            'label' => 'Place of Death',
            'required' => false,
            'help' => 'person.search.cityOfDeath',
        ]);

        $builder->add('viafUrl', TextType::class, [
            'label' => 'VIAF URI',
            'required' => false,
            'help' => 'person.search.viafUrl',
        ]);
        $builder->add('wikipediaUrl', TextType::class, [
            'label' => 'Wikipedia URL',
            'required' => false,
            'help' => 'person.search.wikipediaUrl',
        ]);
        $builder->add('jacksonUrl', TextType::class, [
            'label' => 'Jackson URL',
            'required' => false,
            'help' => 'person.search.jacksonUrl',
        ]);
        $builder->add('imageUrl', TextType::class, [
            'label' => 'Image URL',
            'required' => false,
            'help' => 'person.search.imageUrl',
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
        if ($user) {
            $builder->add('finalcheck', ChoiceType::class, [
                'label' => 'Verified',
                'choices' => [
                    'Yes' => 'Y',
                    'No' => 'N',
                ],
                'label_attr' => [
                    'class' => 'radio-inline',
                ],
                'help' => 'person.search.finalcheck',
                'required' => false,
                'expanded' => true,
                'multiple' => false,
                'empty_data' => null,
                'data' => null,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        parent::configureOptions($resolver);
        $resolver->setRequired(['entity_manager', 'user']);
    }
}
