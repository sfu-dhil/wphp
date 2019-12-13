<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use AppBundle\Entity\TitleRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning persons to titles with roles.
 */
class TitlePersonType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('person', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'person_typeahead',
            'class' => Person::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
            'attr' => array(
                'help_block' => 'person.search.name',
            ),
        ));

        $builder->add('role', EntityType::class, array(
            'class' => Role::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Select a role',
            'attr' => array(
                'help_block' => 'title.person.role',
            ),
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleRole::class,
        ));
    }
}
