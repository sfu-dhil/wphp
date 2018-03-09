<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use AppBundle\Entity\Title;
use AppBundle\Entity\TitleRole;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

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
        ));
        
        $builder->add('role', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'role_typeahead',
            'class' => Role::class,
            'primary_key' => 'id',
            'text_property' => 'name',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',            
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleRole::class
        ));
    }

}
