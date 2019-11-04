<?php

namespace AppBundle\Form\Title;

use AppBundle\Entity\Firm;
use AppBundle\Entity\Firmrole;
use AppBundle\Entity\TitleFirmrole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

/**
 * Subform definition for assigning firms to titles with roles.
 */
class TitleFirmType extends AbstractType {
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('firm', Select2EntityType::class, array(
            'multiple' => false,
            'remote_route' => 'firm_typeahead',
            'class' => Firm::class,
            'primary_key' => 'id',
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'language' => 'en',
        ));

        $builder->add('firmrole', EntityType::class, array(
            'class' => Firmrole::class,
            'choice_label' => 'name',
            'multiple' => false,
            'expanded' => false,
            'placeholder' => 'Select a firm role',
        ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => TitleFirmrole::class,
        ));
    }
}
