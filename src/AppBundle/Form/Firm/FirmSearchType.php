<?php

namespace AppBundle\Form\Firm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The firmSearchtype is a search form for firms.
 */
class FirmSearchType extends AbstractType {
    /**
     * Build the form.
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->setMethod('get');

        $builder->add('name', TextType::class, array(
            'label' => 'Search Firms by Name',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.name',
            ),
        ));

        $builder->add('order', ChoiceType::class, array(
            'label' => 'Results Sorted By',
            'choices' => array(
                'Firm Name (A to Z)' => 'name_asc',
                'Firm Name (Z to A)' => 'name_desc',
                'City (A to Z)' => 'city_asc',
                'City (Z to A)' => 'city_desc',
                'Start Date (Oldest First)' => 'start_asc',
                'Start Date (Youngest First)' => 'start_desc',
                'End Date (Least Recent First)' => 'end_asc',
                'End Date (Most Recent First)' => 'end_desc',
            ),
            'attr' => array(
                'help_block' => 'Choose a sort method for the results',
            ),
            'required' => false,
            'expanded' => false,
            'multiple' => false,
        ));

        $builder->add('id', TextType::class, array(
            'label' => 'Firm ID',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.id',
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
                'help_block' => 'firm.search.gender',
            ),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
            'empty_data' => null,
            'data' => null,
        ));

        $builder->add('address', TextType::class, array(
            'label' => 'Address',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.streetAddress',
            ),
        ));

        $builder->add('city', TextType::class, array(
            'label' => 'City',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.city',
            ),
        ));
        $builder->add('start', TextType::class, array(
            'label' => 'Start Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.startDate',
            ),
        ));
        $builder->add('end', TextType::class, array(
            'label' => 'End Date',
            'required' => false,
            'attr' => array(
                'help_block' => 'firm.search.endDate',
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
    }
}
