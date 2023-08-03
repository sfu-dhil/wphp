<?php

declare(strict_types=1);

namespace App\Form\Title;

use App\Entity\Person;
use App\Entity\Role;
use App\Entity\TitleRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning persons to titles with roles.
 */
class TitlePersonType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) : void {
        $builder->add('person', Select2EntityType::class, [
            'label' => 'Person',
            'multiple' => false,
            'text_property' => 'getFormId',
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'required' => true,
            'help' => 'person.search.name',
            'attr' => [
                'required' => true,
            ],
            'placeholder' => 'Search for an existing person by name',
        ]);

        $builder->add('role', EntityType::class, [
            'label' => 'Person Role',
            'class' => Role::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Select a role',
            'required' => true,
            'help' => 'title.form.titleRoles',
            'attr' => [
                'required' => true,
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) : void {
        $resolver->setDefaults([
            'data_class' => TitleRole::class,
        ]);
    }
}
