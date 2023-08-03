<?php

declare(strict_types=1);

namespace App\Form\Person;

use App\Entity\Firm;
use App\Entity\Geonames;
use App\Entity\Person;
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
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('lastName', null, [
            'label' => 'Last Name',
            'required' => false,
            'help' => 'person.form.lastName',
        ]);
        $builder->add('firstName', null, [
            'label' => 'First Name',
            'required' => false,
            'help' => 'person.form.firstName',
        ]);
        $builder->add('title', null, [
            'label' => 'Title',
            'required' => false,
            'help' => 'person.form.title',
        ]);
        $builder->add('gender', ChoiceType::class, [
            'label' => 'Gender',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Female' => Person::FEMALE,
                'Male' => Person::MALE,
                'Transgender' => Person::TRANS,
                'Unknown' => Person::UNKNOWN,
            ],
            'help' => 'person.form.gender',
        ]);
        $builder->add('dob', null, [
            'label' => 'Date of Birth',
            'required' => false,
            'help' => 'person.form.dob',
        ]);
        $builder->add('dod', null, [
            'label' => 'Death Date',
            'required' => false,
            'help' => 'person.form.dod',
        ]);
        $builder->add('cityOfBirth', Select2EntityType::class, [
            'label' => 'Place of Birth',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'help' => 'person.form.cityOfBirth',
            'placeholder' => 'Search for an existing city by name',
        ]);
        $builder->add('cityOfDeath', Select2EntityType::class, [
            'label' => 'Place of Death',
            'multiple' => false,
            'remote_route' => 'geonames_typeahead',
            'class' => Geonames::class,
            'primary_key' => 'geonameid',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'help' => 'person.form.cityOfDeath',
            'placeholder' => 'Search for an existing city by name',
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
        $builder->add('viafUrl', UrlType::class, [
            'label' => 'VIAF URI',
            'required' => false,
            'help' => 'person.form.viafUrl',
        ]);
        $builder->add('wikipediaUrl', UrlType::class, [
            'label' => 'Wikipedia URL',
            'required' => false,
            'help' => 'person.form.wikipediaUrl',
        ]);
        $builder->add('imageUrl', UrlType::class, [
            'label' => 'Image URL',
            'required' => false,
            'help' => 'person.form.imageUrl',
        ]);
        $builder->add('notes', null, [
            'label' => 'Notes',
            'required' => false,
            'help' => 'person.form.notes',
        ]);
        $builder->add('finalcheck', ChoiceType::class, [
            'label' => 'Verified',
            'expanded' => true,
            'multiple' => false,
            'choices' => [
                'Yes' => true,
                'No' => false,
            ],
            'required' => true,
            'placeholder' => false,
            'help' => 'person.form.finalCheck',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => Person::class,
        ]);
    }
}
