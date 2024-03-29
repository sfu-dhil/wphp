<?php

declare(strict_types=1);

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
class FirmSearchType extends AbstractType {
    /**
     * Build the form.
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $user = $options['user'];
        $builder->setMethod('get');

        $builder->add('name', TextType::class, [
            'label' => 'Search Firms by Name',
            'required' => false,
            'help' => 'firm.search.name',
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
            'help' => 'Choose a sort method for the results',
            'required' => false,
            'expanded' => false,
            'multiple' => false,
        ]);

        $builder->add('id', TextType::class, [
            'label' => 'Firm ID',
            'required' => false,
            'help' => 'firm.search.id',
        ]);

        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'choices' => [
                'Female' => 'F',
                'Male' => 'M',
                'Unknown' => 'U',
            ],
            'label_attr' => [
                'class' => 'checkbox-inline',
            ],
            'help' => 'firm.search.gender',
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $builder->add('address', TextType::class, [
            'label' => 'Address',
            'required' => false,
            'help' => 'firm.search.streetAddress',
        ]);

        $builder->add('city', TextType::class, [
            'label' => 'City',
            'required' => false,
            'help' => 'firm.search.city',
        ]);
        $builder->add('start', TextType::class, [
            'label' => 'Start Date',
            'required' => false,
            'help' => 'firm.search.startDate',
        ]);
        $builder->add('end', TextType::class, [
            'label' => 'End Date',
            'required' => false,
            'help' => 'firm.search.endDate',
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
                'help' => 'firm.search.finalcheck',
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
        $resolver->setRequired(['user']);
    }
}
