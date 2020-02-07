<?php

declare(strict_types=1);

/*
 * (c) 2020 Michael Joyce <mjoyce@sfu.ca>
 * This source file is subject to the GPL v2, bundled
 * with this source code in the file LICENSE.
 */

namespace App\Form\Firm;

use App\Entity\Firm;
use App\Entity\Geonames;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Form definition for the firm class.
 */
class FirmType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('name', null, [
            'label' => 'Name',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.form.name',
            ],
        ]);
        $builder->add('gender', ChoiceType::class, [
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Female' => Firm::FEMALE,
                'Male' => Firm::MALE,
                'Unknown' => Firm::UNKNOWN,
            ],
            'empty_data' => Firm::UNKNOWN,
            'attr' => [
                'help_block' => 'firm.form.gender',
            ],
        ]);
        $builder->add('streetAddress', null, [
            'label' => 'Street Address',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.form.streetAddress',
            ],
        ]);
        $builder->add('city', Select2EntityType::class, [
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => [
                'help_block' => 'firm.form.city',
            ],
        ]);
        $builder->add('startDate', null, [
            'label' => 'Start Date',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.form.startDate',
            ],
        ]);
        $builder->add('endDate', null, [
            'label' => 'End Date',
            'required' => false,
            'attr' => [
                'help_block' => 'firm.form.endDate',
            ],
        ]);
        $builder->add('finalcheck', ChoiceType::class, [
            'label' => 'Firm Finalcheck',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'attr' => [
                'help_block' => 'firm.form.finalCheck',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Firm::class,
        ]);
    }
}
