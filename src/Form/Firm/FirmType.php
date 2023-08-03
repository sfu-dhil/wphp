<?php

declare(strict_types=1);

namespace App\Form\Firm;

use App\Entity\Firm;
use App\Entity\Geonames;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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
            'help' => 'firm.form.name',
        ]);
        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Female' => Firm::FEMALE,
                'Male' => Firm::MALE,
                'Unknown' => Firm::UNKNOWN,
            ],
            'empty_data' => Firm::UNKNOWN,
            'help' => 'firm.form.gender',
        ]);
        $builder->add('streetAddress', null, [
            'label' => 'Street Address',
            'required' => false,
            'help' => 'firm.form.streetAddress',
        ]);
        $builder->add('city', Select2EntityType::class, [
            'label' => 'City',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'help' => 'firm.form.city',
            'placeholder' => 'Search for an existing city by name',
        ]);
        $builder->add('startDate', null, [
            'label' => 'Start Date',
            'required' => false,
            'help' => 'firm.form.startDate',
        ]);
        $builder->add('endDate', null, [
            'label' => 'End Date',
            'required' => false,
            'help' => 'firm.form.endDate',
        ]);
        $builder->add('firmSources', CollectionType::class, [
            'label' => 'Firm Sources',
            'required' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'delete_empty' => true,
            'entry_type' => FirmSourceType::class,
            'entry_options' => [
                'label' => false,
            ],
            'by_reference' => false,
            'help' => 'firm.form.firmSources',
            'attr' => [
                'class' => 'collection collection-complex',
            ],
        ]);

        $builder->add('relatedFirms', Select2EntityType::class, [
            'label' => 'Related Firms',
            'text_property' => 'getFormId',
            'multiple' => true,
            'remote_route' => 'firm_typeahead',
            'class' => Firm::class,
            'allow_clear' => true,
            'help' => 'person.form.relatedFirms',
            'placeholder' => 'Search for an existing firm by name',
        ]);
        $builder->add('notes', null, [
            'label' => 'Notes',
            'required' => false,
            'help' => 'person.form.notes',
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
            'help' => 'firm.form.finalCheck',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Firm::class,
        ]);
    }
}
