<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Firm;

use App\Form\Person\PersonFilterType;
use App\Form\Title\TitleFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * A search form for firms.
 */
class FirmSearchType extends AbstractType
{
    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->setMethod('get');

        $builder->add('name', TextType::class, [
            'label' => 'Search Firms by Name',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.name',
            ],
        ]);

        $builder->add('order', ChoiceType::class, [
            'label' => 'Results Sorted By',
            'choices' => [
                'Firm Name (A to Z)' => 'name_asc',
                'Firm Name (Z to A)' => 'name_desc',
                'City (A to Z)' => 'city_asc',
                'City (Z to A)' => 'city_desc',
                'Start Date (Oldest First)' => 'start_asc',
                'Start Date (Youngest First)' => 'start_desc',
                'End Date (Least Recent First)' => 'end_asc',
                'End Date (Most Recent First)' => 'end_desc',
            ],
            'attr' => [
                'help_block' => 'Choose a sort method for the results',
            ],
            'required' => false,
            'expanded' => false,
            'multiple' => false,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Firm ID',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.id',
            ],
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => 'F',
                'Male' => 'M',
                'Unknown' => 'U',
            ],
            'attr' => [
                'help_block' => 'firm.search.gender',
            ],
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('address', TextType::class, [
            'label' => 'Address',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.streetAddress',
            ],
        ]);

        $builder->add('city', TextType::class, [
            'label' => 'City',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.city',
            ],
        ]);
        $builder->add('start', TextType::class, [
            'label' => 'Start Date',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.startDate',
            ],
        ]);
        $builder->add('end', TextType::class, [
            'label' => 'End Date',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.search.endDate',
            ],
        ]);

        $builder->add('title_filter', TitleFilterType::class, [
            'label' => 'Filter by Title',
            'required' => false,
            'attr' => [
                'class' => 'embedded-form',
            ],
        ]);

        $builder->add('person_filter', PersonFilterType::class, [
            'label' => 'Filter by Person',
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
    }
}
